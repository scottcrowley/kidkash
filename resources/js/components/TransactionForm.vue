<template>
    <div>
        <div class="form-group row">
            <label for="owner_id" class="col-4 w-1/3 text-left md:text-right">Owner</label>

            <div class="col-6 w-2/3">
                <div class="relative">
                    <select v-if="owners.length" name="owner_id" v-model="transactionData.owner_id" class="w-full" :class="checkFieldError('owner_id') ? 'is-invalid' : ''" required>
                        <option value="0">Choose an Owner</option>
                        <option 
                            v-for="owner in owners"
                            :value="owner.id" 
                        >{{ owner.name }}</option>
                    </select>
                    <select v-else name="owner_id" class="w-full" :class="checkFieldError('owner_id') ? 'is-invalid' : ''" required>
                        <option value="">No Owners in Database</option>
                    </select>
                </div>
                <span class="alert-danger" role="alert" v-if="checkFieldError('owner_id')">
                    <strong v-text="errors.owner_id[0]"></strong>
                </span>
            </div>
        </div>

        <div class="form-group row">
            <label for="vendor_id" class="col-4 w-1/3 text-left md:text-right">Vendor</label>

            <div class="col-6 w-2/3">
                <div class="relative">
                    <select v-if="vendors.length" name="vendor_id" v-model="transactionData.vendor_id" @change="updateCardList" class="w-full" :class="checkFieldError('vendor_id') ? 'is-invalid' : ''" required>
                        <option value="0">Choose a Vendor</option>
                        <option 
                            v-for="vendor in vendors"
                            :value="vendor.id" 
                        >{{ vendor.name }}</option>
                    </select>
                    <select v-else name="vendor_id" class="w-full" :class="checkFieldError('vendor_id') ? 'is-invalid' : ''" required>
                        <option value="">No Vendors in Database</option>
                    </select>
                </div>
                <span class="alert-danger" role="alert" v-if="checkFieldError('vendor_id')">
                    <strong v-text="errors.vendor_id[0]"></strong>
                </span>
            </div>
        </div>

        <div class="form-group row">
            <label for="type" class="col-4 w-1/3 text-left md:text-right">Transaction Type</label>

            <div class="col-6 w-2/3">
                <div class="relative">
                    <select name="type" v-model="transactionData.type" class="w-full" :class="checkFieldError('type') ? 'is-invalid' : ''" required>
                        <option value="0">Choose a Type</option>
                        <option value="add">Adding Money</option>
                        <option value="use">Using Money</option>
                    </select>
                </div>
                <span class="alert-danger" role="alert" v-if="checkFieldError('type')">
                    <strong v-text="errors.type[0]"></strong>
                </span>
            </div>
        </div>

        <div class="form-group row">
            <label for="description" class="col-4 w-1/3 text-left md:text-right">Description</label>

            <div class="col-6 w-2/3">
                <textarea rows="4" name="description" v-model="transactionData.description" :class="checkFieldError('description') ? 'is-invalid' : ''" class="form-input"></textarea>

                <span class="alert-danger" role="alert" v-if="checkFieldError('description')">
                    <strong v-text="errors.description[0]"></strong>
                </span>
            </div>
        </div>

        <div class="form-group row">
            <label for="number" class="col-4 w-1/3 text-left md:text-right">Available Gift Cards</label>

            <div class="col-6 w-2/3">
                <div class="relative">
                    <select name="cards" class="w-full"  v-if="cardList.length" v-model="cardSelected" @change="cardSelect">
                        <option value="">No Card</option>
                        <option value="new">New Card</option>
                        <option 
                            v-for="(card, index) in cardList"
                            :value="index" 
                        >{{ card.vendor.name }} 
                            (ending in {{ 
                                (card.number.substr(-4)) + ')' + 
                                (card.expiration != null ? ' Exp: '+card.expiration : '') + 
                                ' Bal: $' + (card.balance > 0 ? card.balance.toFixed(2) : 0)
                            }}
                        </option>
                    </select>
                    <select v-else name="cards" class="w-full" v-model="cardSelected" @change="cardSelect">
                        <option value="">No Card</option>
                        <option value="new">New Card</option>
                    </select>
                </div>
            </div>
        </div>

        <div v-show="showNewCard" class="ml-8">
            <div class="form-group row">
                <label for="number" class="col-4 w-1/3 text-left md:text-right">Card Number</label>

                <div class="col-6 w-2/3">
                    <input type="text" name="number" v-model="transactionData.number" :class="checkFieldError('number') ? 'is-invalid' : ''" class="form-input" autocomplete="off">

                    <span class="alert-danger" role="alert" v-if="checkFieldError('number')">
                        <strong v-text="errors.number[0]"></strong>
                    </span>
                </div>
            </div>

            <div class="form-group row">
                <label for="pin" class="col-4 w-1/3 text-left md:text-right">Card Pin</label>

                <div class="col-6 w-2/3">
                    <input type="text" name="pin" v-model="transactionData.pin" :class="checkFieldError('pin') ? 'is-invalid' : ''" class="form-input" autocomplete="off">

                    <span class="alert-danger" role="alert" v-if="checkFieldError('pin')">
                        <strong v-text="errors.pin[0]"></strong>
                    </span>
                </div>
            </div>

            <div class="form-group row">
                <label for="expiration" class="col-4 w-1/3 text-left md:text-right">Expiration (mm/yyyy)</label>

                <div class="col-6 w-2/3">
                    <input type="text" name="expiration" v-model="transactionData.expiration" :class="checkFieldError('expiration') ? 'is-invalid' : ''" class="form-input" autocomplete="off">

                    <span class="alert-danger" role="alert" v-if="checkFieldError('expiration')">
                        <strong v-text="errors.expiration[0]"></strong>
                    </span>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <label for="amount" class="col-4 w-1/3 text-left md:text-right">Amount</label>

            <div class="col-6 w-2/3">
                <input type="number" min="0.01" step="0.01" name="amount" v-model="transactionData.amount" :class="checkFieldError('amount') ? 'is-invalid' : ''" class="form-input" autocomplete="off" required>

                <span class="alert-danger" role="alert" v-if="checkFieldError('amount')">
                    <strong v-text="errors.amount[0]"></strong>
                </span>
            </div>
        </div>

        <div class="form-group row mb-0">
            <div class="offset-4 flex">
                <button type="submit" class="btn is-primary" v-text="submitLabel"></button>
                <a :href="redirectPath" class="btn ml-2 border border-secondary-300">
                    Cancel
                </a>
                <div class="ml-auto" v-if="action == 'update'">
                    <delete-confirm-button label="Delete Transaction" classes="btn btn-text" :path="'/transactions/'+ transactionData.id" redirect-path="/transactions" class="inline">
                        <div slot="title">Are You Sure?</div>  
                        Are you sure you want to delete this transaction from the database?
                    </delete-confirm-button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import DeleteConfirmButton from './DeleteConfirmButton';

export default {
    props: [
        'action', 'transaction', 'owners', 'vendors', 'cards', 'errors', 'redirectPath', 'preselectedCard'

    ],
    components: {
        DeleteConfirmButton,
    },
    data() {
        return {
            transactionData: this.transaction,
            cardList: this.cards,
            showNewCard: false,
            cardSelected: ''
        }
    },
    computed: {
        submitLabel() {
            return (this.action == 'update') ? 'Update Transaction' : 'Add Transaction' 
        }
    },
    created () {
        if (this.preselectedCard || this.transactionData.vendor_id) {
            this.transactionData.type = (this.preselectedCard) ? 'use' : 0;
            this.updateSelects(this.transactionData.vendor_id);
        } else {
            this.checkSelectedCard();
        }

        this.showNewCard = this.errorExists();

        if (this.action == 'update') {
            this.transactionData.type = (this.transactionData.amount < 0) ? 'use' : 'add';
            this.transactionData.amount = Math.abs(this.transactionData.amount);
        }
        
        this.transactionData.owner_id = (this.action != 'update' && ! this.transactionData.owner_id) ? 0 : this.transactionData.owner_id;
        this.transactionData.vendor_id = (this.action != 'update' && ! this.transactionData.vendor_id) ? 0 : this.transactionData.vendor_id;
    },
    methods: {
        errorExists() {
            return (typeof this.errors === 'object' && this.errors !== null && Object.keys(this.errors).length > 0);
        },
        checkFieldError(field) {
            return (this.errorExists() && this.errors[field]);
        },
        checkSelectedCard() {
            this.cardSelected = '';

            if (this.cardList.length) {
                this.cardList.forEach((card, index) => {
                    if ((card.number == this.transactionData.number) || (this.preselectedCard !== null && card.id == this.preselectedCard)) {
                        this.cardSelected = index;
                        this.transactionData.number = card.number;
                        this.transactionData.pin = card.pin;
                    }
                });
            }
        },
        cardSelect(e) {
            let value = e.target.value;
            this.transactionData.number = '';
            this.transactionData.pin = '';
            this.showNewCard = (value == 'new') ? true : false;

            if (value == 'new' || value == '') {
                return;
            }

            let card = this.cardList[value];
            
            this.transactionData.number = card.number;
            this.transactionData.pin = card.pin;

            this.updateSelects(card.vendor_id);
        },
        updateSelects(vendor) {
            this.transactionData.vendor_id = vendor;
            this.updateCardList();
        },
        updateCardList() {
            axios.get(`/api/cards/${this.transactionData.vendor_id}`)
                .then(response => {
                    this.cardList = response.data;

                    this.checkSelectedCard();
                });
        }
    },
}
</script>