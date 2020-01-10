<template>
    <div>
        <div class="form-group row">
            <label for="owner_id" class="col-4 w-1/3 text-left md:text-right">Transfer From</label>

            <div class="col-6 w-2/3">
                <div class="relative">
                    <select 
                        v-if="owners.length" 
                        v-model="fromTransactionData.owner_id" 
                        @change="selectOwner"
                        name="owner_id" 
                        class="w-full" 
                        :class="checkError('owner_id') ? 'is-invalid' : ''" 
                        required
                    >
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
            <label for="owner_id" class="col-4 w-1/3 text-left md:text-right">Transfer To</label>

            <div class="col-6 w-2/3">
                <div class="relative">
                    <select 
                        v-model="toTransactionData.owner_id" 
                        name="owner_id" 
                        class="w-full" 
                        :class="toDisabled ? 'disabled' : ''" 
                        :disabled="toDisabled"
                        required
                    >
                        <option value="0">Choose an Owner</option>
                        <option 
                            v-for="owner in toOwners"
                            :value="owner.id" 
                        >{{ owner.name }}</option>
                    </select>
                    <div class="select-menu-icon">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" :class="toDisabled ? 'text-secondary-400' : ''">
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
                    <select 
                        v-model="fromTransactionData.vendor_id" 
                        @change="updateCardList" 
                        name="vendor_id" 
                        class="w-full" 
                        :class="vendorDisabled ? 'disabled' : ''" 
                        :disabled="vendorDisabled"
                        required
                    >
                        <option value="0">Choose a Vendor</option>
                        <option 
                            v-for="vendor in vendorsList"
                            :value="vendor.id" 
                        >{{ vendor.name }}</option>
                    </select>
                    <div class="select-menu-icon">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" :class="vendorDisabled ? 'text-secondary-400' : ''">
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
            <label for="description" class="col-4 w-1/3 text-left md:text-right">Description</label>

            <div class="col-6 w-2/3">
                <textarea rows="4" name="description" v-model="fromTransactionData.description" :class="checkError('description') ? 'is-invalid' : ''" class="form-input"></textarea>

                <span class="alert-danger" role="alert" v-if="checkError('description')">
                    <strong v-text="errors.description[0]"></strong>
                </span>
            </div>
        </div>

        <div class="form-group row">
            <label for="number" class="col-4 w-1/3 text-left md:text-right">Available Gift Cards</label>

            <div class="col-6 w-2/3">
                <div class="relative">
                    <select 
                        v-model="cardSelected" 
                        @change="cardSelect"
                        name="cards" 
                        class="w-full"  
                        :class="cardsDisabled ? 'disabled' : ''" 
                        :disabled="cardsDisabled"
                    >
                        <option value="">No Card</option>
                        <option value="new">New Card</option>
                        <option 
                            v-for="(card, index) in cardList"
                            :value="index" 
                        >{{ card.vendor.name }} (ending in {{card.number.substr(-5)}}) ${{ card.balance.toFixed(2) }}</option>
                    </select>
                    <div class="select-menu-icon">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" :class="cardsDisabled ? 'text-secondary-400' : ''">
                            <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div v-show="showNewCard" class="ml-8">
            <div class="form-group row">
                <label for="number" class="col-4 w-1/3 text-left md:text-right">Card Number</label>

                <div class="col-6 w-2/3">
                    <input type="text" name="number" v-model="fromTransactionData.number" :class="checkError('number') ? 'is-invalid' : ''" class="form-input">

                    <span class="alert-danger" role="alert" v-if="checkError('number')">
                        <strong v-text="errors.number[0]"></strong>
                    </span>
                </div>
            </div>

            <div class="form-group row">
                <label for="pin" class="col-4 w-1/3 text-left md:text-right">Card Pin</label>

                <div class="col-6 w-2/3">
                    <input type="text" name="pin" v-model="fromTransactionData.pin" :class="checkError('pin') ? 'is-invalid' : ''" class="form-input">

                    <span class="alert-danger" role="alert" v-if="checkError('pin')">
                        <strong v-text="errors.vendor_id[0]"></strong>
                    </span>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <label for="amount" class="col-4 w-1/3 text-left md:text-right">Amount</label>

            <div class="col-6 w-2/3">
                <input type="number" min="0.01" step="0.01" name="amount" v-model="fromTransactionData.amount" :class="checkError('amount') ? 'is-invalid' : ''" class="form-input" autocomplete="off" required>

                <span class="alert-danger" role="alert" v-if="checkError('amount')">
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
                    <delete-confirm-button label="Delete Transaction" classes="btn btn-text" :path="'/transfers/'+ transfer.id" redirect-path="/transactions" class="inline">
                        <div slot="title">Are You Sure?</div>  
                        Are you sure you want to delete this transfer from the database?
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
        'action', 'fromTransaction', 'toTransaction', 'transfer', 'owners', 'vendors', 'cards', 'errors', 'redirectPath'

    ],
    components: {
        DeleteConfirmButton,
    },
    data() {
        return {
            fromTransactionData: this.fromTransaction,
            toTransactionData: this.toTransaction,
            transferData: this.transfer,
            cardList: [],
            toOwners: [],
            vendorsList: [],
            showNewCard: false,
            cardSelected: '',
            toDisabled: true,
            vendorDisabled: true,
            cardsDisabled: true,
            vendorIds: '',
        }
    },
    computed: {
        submitLabel() {
            return (this.action == 'update') ? 'Update Transfer' : 'Add Transfer' 
        },
    },
    created () {
        this.checkSelectedCard();

        if (this.fromTransactionData.amount < 0 && this.fromTransactionData.type == 'use') {
            this.fromTransactionData.amount = Math.abs(this.fromTransactionData.amount);
        }
        
        this.fromTransactionData.owner_id = (this.action != 'update' && ! this.fromTransactionData.owner_id) ? 0 : this.fromTransactionData.owner_id;
        this.toTransactionData.owner_id = (this.action != 'update' && ! this.toTransactionData.owner_id) ? 0 : this.toTransactionData.owner_id;
        this.fromTransactionData.vendor_id = (this.action != 'update' && ! this.fromTransactionData.vendor_id) ? 0 : this.fromTransactionData.vendor_id;
        this.toTransactionData.vendor_id = (this.action != 'update' && ! this.toTransactionData.vendor_id) ? 0 : this.toTransactionData.vendor_id;

        this.updateSelectsState();
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
                    if (card.number == this.fromTransactionData.number) {
                        this.cardSelected = index;
                    }
                });
            }
        },
        cardSelect(e) {
            let value = e.target.value;
            this.resetCard();

            this.showNewCard = (value == 'new') ? true : false;

            if (value == 'new' || value == '') {
                return;
            }

            let card = this.cardList[value];
            
            this.fromTransactionData.number = card.number;
            this.fromTransactionData.pin = card.pin;

            if (this.fromTransactionData.vendor_id != card.vendor_id) {
                this.fromTransactionData.vendor_id = card.vendor_id;
                this.updateCardList();
                return;
            }
            this.checkSelectedCard();
        },
        updateCardList() {
            let vendors = (this.fromTransactionData.vendor_id > 0) ? this.fromTransactionData.vendor_id : this.vendorIds;
            let endpoint = '/api/cards/' + vendors + ((this.fromTransactionData.owner_id > 0) ? '/' + this.fromTransactionData.owner_id : '');

            axios.get(endpoint)
                .then(response => {
                    this.cardList = response.data;

                    this.checkSelectedCard();
                });
        },
        updateSelectsState() {
            this.toDisabled = this.fromTransactionData.owner_id == 0;
            this.vendorDisabled = this.fromTransactionData.owner_id == 0;
            this.cardsDisabled = this.fromTransactionData.owner_id == 0;
        },
        selectOwner(e) {
            let owner = e.target.value;
            this.toTransactionData.owner_id = 0;
            this.fromTransactionData.vendor_id = 0;
            this.resetCard();

            if (owner == 0) {
                this.updateSelectsState();
                return;
            }
            this.updateToOwners(owner);
        },
        updateToOwners(owner) {
            axios.get(`/api/users/${owner}`)
                .then(response => {
                    this.toOwners = response.data;
                    this.updateVendors(owner);

                });
        },
        updateVendors(owner) {
            axios.get(`/api/users/${owner}/vendors`)
                .then(response => {
                    let vendorIds = [];
                    this.vendorsList = response.data;
                    if (!this.vendorsList.length) {
                        return;
                    }
                    this.vendorsList.forEach((vendor, index) => {
                        vendorIds.push(vendor.id);
                    });
                    this.vendorIds = vendorIds.join(',');

                    this.updateCardList();
                    this.updateSelectsState();
                });
        },
        resetCard() {
            this.cardSelected = '';
            this.fromTransactionData.number = '';
            this.fromTransactionData.pin = '';
        },
    },
}
</script>