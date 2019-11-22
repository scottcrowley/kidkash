<template>
    <div>
        <div class="form-group row">
            <label for="owner_id" class="col-4 w-1/3 text-left md:text-right">Owner</label>

            <div class="col-6 w-2/3">
                <div class="relative">
                    <select v-if="owners.length" name="owner_id" v-model="transactionData.owner_id" class="w-full" :class="checkError('owner_id') ? 'is-invalid' : ''" required>
                        <option value="0">Choose an Owner</option>
                        <option 
                            v-for="owner in owners"
                            :value="owner.id" 
                        >{{ owner.name }}</option>
                    </select>
                    <select v-else name="owner_id" class="w-full" :class="checkError('owner_id') ? 'is-invalid' : ''" required>
                        <option value="">No Owners in Database</option>
                    </select>
                    <div class="select-menu-icon">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                        </svg>
                    </div>
                </div>
                <span class="alert-danger" role="alert" v-if="checkError('owner_id')">
                    <strong v-text="errors.owner_id[0]"></strong>
                </span>
            </div>
        </div>

        <div class="form-group row">
            <label for="vendor_id" class="col-4 w-1/3 text-left md:text-right">Vendor</label>

            <div class="col-6 w-2/3">
                <div class="relative">
                    <select v-if="vendors.length" name="vendor_id" v-model="transactionData.vendor_id" @change="updateCardList" class="w-full" :class="checkError('vendor_id') ? 'is-invalid' : ''" required>
                        <option value="0">Choose a Vendor</option>
                        <option 
                            v-for="vendor in vendors"
                            :value="vendor.id" 
                        >{{ vendor.name }}</option>
                    </select>
                    <select v-else name="vendor_id" class="w-full" :class="checkError('vendor_id') ? 'is-invalid' : ''" required>
                        <option value="">No Vendors in Database</option>
                    </select>
                    <div class="select-menu-icon">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                        </svg>
                    </div>
                </div>
                <span class="alert-danger" role="alert" v-if="checkError('vendor_id')">
                    <strong v-text="errors.vendor_id[0]"></strong>
                </span>
            </div>
        </div>

        <div class="form-group row">
            <label for="type" class="col-4 w-1/3 text-left md:text-right">Transaction Type</label>

            <div class="col-6 w-2/3">
                <div class="relative">
                    <select name="type" v-model="transactionData.type" class="w-full" :class="checkError('owner_id') ? 'is-invalid' : ''" required>
                        <option value="0">Choose a Type</option>
                        <option value="add">Adding Money</option>
                        <option value="use">Using Money</option>
                    </select>
                    <div class="select-menu-icon">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                        </svg>
                    </div>
                </div>
                <span class="alert-danger" role="alert" v-if="checkError('type')">
                    <strong v-text="errors.type[0]"></strong>
                </span>
            </div>
        </div>

        <div class="form-group row">
            <label for="description" class="col-4 w-1/3 text-left md:text-right">Description</label>

            <div class="col-6 w-2/3">
                <textarea rows="4" name="description" v-model="transactionData.description" :class="checkError('description') ? 'is-invalid' : ''" class="form-input"></textarea>

                <span class="alert-danger" role="alert" v-if="checkError('description')">
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
                        >{{ card.vendor.name }} (ending in {{card.number.substr(-5)}})</option>
                    </select>
                    <select v-else name="cards" class="w-full" v-model="cardSelected" @change="cardSelect">
                        <option value="">No Card</option>
                        <option value="new">New Card</option>
                    </select>
                    <div class="select-menu-icon">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div v-show="showCardNumber">
            <div class="form-group row">
                <label for="number" class="col-4 w-1/3 text-left md:text-right">Card Number</label>

                <div class="col-6 w-2/3">
                    <input type="text" name="number" v-model="transactionData.number" :class="checkError('number') ? 'is-invalid' : ''" class="form-input">

                    <span class="alert-danger" role="alert" v-if="checkError('number')">
                        <strong v-text="errors.number[0]"></strong>
                    </span>
                </div>
            </div>

            <div class="form-group row">
                <label for="pin" class="col-4 w-1/3 text-left md:text-right">Card Pin</label>

                <div class="col-6 w-2/3">
                    <input type="text" name="pin" v-model="transactionData.pin" :class="checkError('pin') ? 'is-invalid' : ''" class="form-input">

                    <span class="alert-danger" role="alert" v-if="checkError('pin')">
                        <strong v-text="errors.vendor_id[0]"></strong>
                    </span>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <label for="amount" class="col-4 w-1/3 text-left md:text-right">Amount</label>

            <div class="col-6 w-2/3">
                <input type="number" min="0.01" step="0.01" name="amount" v-model="transactionData.amount" :class="checkError('amount') ? 'is-invalid' : ''" class="form-input" required>

                <span class="alert-danger" role="alert" v-if="checkError('amount')">
                    <strong v-text="errors.amount[0]"></strong>
                </span>
            </div>
        </div>

        <div class="form-group row mb-0">
            <div class="offset-4 flex">
                <button type="submit" class="btn is-primary" v-text="submitLabel"></button>
                <a href="/transactions" class="btn ml-2 border border-secondary-300">
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
        'action', 'transaction', 'owners', 'vendors', 'cards', 'errors'

    ],
    components: {
        DeleteConfirmButton,
    },
    data() {
        return {
            transactionData: this.transaction,
            cardList: this.cards,
            showCardNumber: false,
            cardSelected: '',
        }
    },
    computed: {
        submitLabel() {
            return (this.action == 'update') ? 'Update Transaction' : 'Add Transaction' 
        }
    },
    created () {
        this.checkSelectedCard();

        if (this.transactionData.amount < 0 && this.transactionData.type == 'use') {
            this.transactionData.amount = Math.abs(this.transactionData.amount);
        }
        
        this.transactionData.owner_id = (this.action != 'update' && ! this.transactionData.owner_id) ? 0 : this.transactionData.owner_id;
        this.transactionData.vendor_id = (this.action != 'update' && ! this.transactionData.vendor_id) ? 0 : this.transactionData.vendor_id;
    },
    methods: {
        checkError(field) {
            if (typeof this.errors === 'object' && this.errors !== null && this.errors[field]) {
                return true;
            }
            return false;
        },
        checkSelectedCard() {
            this.cardSelected = '';

            if (this.cardList.length) {
                this.cardList.forEach((card, index) => {
                    if (card.number == this.transactionData.number) {
                        this.cardSelected = index;
                    }
                });
            }
        },
        cardSelect(e) {
            let value = e.target.value;
            this.transactionData.number = '';
            this.transactionData.pin = '';
            this.showCardNumber = (value == 'new') ? true : false;

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