<template>
<div>
    <div class="calculation">
        <div class="col-sm-12 col-lg-6 fields">
            <div class="field col-sm-12 col-lg-6">
                <div class="label_input">
                    <label for="value" class="label_opis">Pożyczona kwota</label>
                    <input id="value" class="input" v-model="valueAmount">
               </div>
            </div>
            <div class="field col-sm-12 col-lg-6">
                <div class="label_input">
                    <label for="years" class="label_opis">Liczba lat</label>
                    <input id="years" class="input" v-model="years">
                </div>
            </div>
            <div class="field col-sm-12 col-lg-6">
                <div class="label_input">
                    <label for ="percent" class="label_opis">Oprocentowanie</label>
                    <input id="percent" class="input" v-model="percent">
                </div>
            </div>
            <div class="field col-sm-12 col-lg-6">
                <div class="label_input">
                    <label for ="paymentsInYear" class="label_opis">Liczba spłat w roku</label>
                    <input id="paymentsInYear" class="input" v-model="paymentsInYear">
                </div>
            </div>
        </div>
        <div class ="col-sm-12 col-lg-6 installmentType">
            <div class="installment_type_describe">
                Wybierz typ raty
            </div>
            <div class="fixedInstallment">
                <input type="radio" id ="fixedInstallment" name="installment_type"  value ="fixed"
                       v-model ="installmentType">
                <label for="fixedInstallment">Rata stała</label>
            </div>
            <div class="decreasingInstallment">
               <input type="radio" id="decreasingInstallment" name="installment_type" value ="decreasing"
                      v-model ="installmentType">
               <label for="decreasingInstallment">Rata malejąca</label>
            </div>
        </div>
        <div>
            <button class="calculateButton" @click="calculate()">Oblicz</button>
        </div>

    </div>

    <modal title="Obliczenia raty" width ="720px" key="modalCalculationShow"
            @exit="modalCalculationShow = false"
            :showExit="true"
            :showFooter="false"
            v-if="modalCalculationShow">
        <template slot="body">
            <div>
                <div class="modal-wait">
                    <div v-if="errorMessage === null">
                        <p>Koszt całkowity kredytu wyniesie {{totalAmountToPay}} PLN</p>
                        <tr>
                            <th class="title" style="width: 70px">Rata</th>
                            <th class="title">Kwota raty</th>
                            <th class="title">Kapitał</th>
                            <th class="title">Odsetki</th>
                        </tr>

                        <tr v-for="payment in paymentSchedule">
                            <td style="width: 70px">{{payment.index}}</td>
                            <td>{{payment.installment}}</td>
                            <td class="interest_capital">{{payment.capitalInstallment}}</td>
                            <td class="interest_installment"> {{payment.interestInstallment}}</td>
                        </tr>
                    </div>
                    <div v-if="errorMessage !== null">
                        <p>{{ errorMessage }}</p>
                    </div>
                    <!-- Dodać walidację, zeby wykonywać kalkulację tylko dla kredytów >= 4 lat
                    jest to potrzebne bo inaczej poniższy front się wywala !-->
                    <div class="numerations">
                        <template v-if="years > 3">
                            <div v-if="currentPage === 1" @click="calculate(1)" class="numeration_years active">
                                1
                            </div>
                            <template v-if="currentPage === 2">
                                <div @click="calculate(currentPage - 1)" class="numeration_years">
                                    {{ currentPage - 1}}
                                </div>
                                <div @click="calculate(currentPage)" class="numeration_years active">
                                    {{ currentPage}}
                                </div>
                            </template>

                            <template v-if="currentPage > 2">
                                <div v-if="currentPage > 3" @click="calculate(1)" class="numeration_years">
                                    1
                                </div>
                                <div @click="calculate(currentPage -2)" class="numeration_years">
                                    {{ currentPage - 2 }}
                                </div>
                                <div @click="calculate(currentPage -1)" class="numeration_years">
                                    {{ currentPage - 1 }}
                                </div>
                                <div @click="calculate(currentPage)" class="numeration_years active">
                                    {{ currentPage }}
                                </div>
                            </template>

                            <div v-if="currentPage !== years"
                                 @click="calculate(currentPage + 1)"
                                 class="numeration_years">
                                {{ currentPage + 1 }}
                            </div>


                            <template v-if="currentPage + 1 < years">
                                <div v-if="currentPage + 2 < years"
                                     @click="calculate(currentPage + 2)"
                                     class="numeration_years">
                                    {{ currentPage + 2 }}
                                </div>
                                <div @click="calculate(years)" class="numeration_years">
                                    {{ years }}
                                </div>
                            </template>
                        </template>

                        <template v-if="currentPage===3 && years===3">
                            <div @click="calculate(currentPage - 2)" class="numeration_years">
                                {{ currentPage - 2 }}
                            </div>
                            <div @click="calculate(currentPage - 1)" class="numeration_years">
                                {{ currentPage - 1}}
                            </div>
                            <div @click="calculate(currentPage)" class="numeration_years active">
                                {{ currentPage }}
                            </div>

                        </template>

                        <template v-if="currentPage===2 && years===3">
                            <div @click="calculate(currentPage - 1)" class="numeration_years">
                                {{ currentPage - 1 }}
                            </div>
                            <div @click="calculate(currentPage)" class="numeration_years active">
                                {{ currentPage}}
                            </div>
                            <div @click="calculate(currentPage + 1)" class="numeration_years">
                                {{ currentPage + 1 }}
                            </div>

                        </template>

                        <template v-if="currentPage===1 && years===3">
                            <div @click="calculate(currentPage)" class="numeration_years active">
                                {{ currentPage }}
                            </div>
                            <div @click="calculate(currentPage + 1)" class="numeration_years">
                                {{ currentPage + 1 }}
                            </div>
                            <div @click="calculate(currentPage + 2)" class="numeration_years">
                                {{ currentPage + 2 }}
                            </div>
                        </template>
                    </div>

                </div>
            </div>
        </template>
    </modal>
</div>


</template>

<script>
import axios from 'axios';
import modal from './global/Modal'

export default {
    name: "Main",
    data(){
        return {
            valueAmount: null,
            years: null,
            percent: null,
            paymentsInYear: null,
            totalAmountToPay: null,
            installment: null,
            paymentSchedule: null,
            modalCalculationShow: false,
            installmentType: 'fixed',
            errorMessage: null,
            currentPage: 1,
            chosenPage: 1,
            secondButtonChosen: false,
            pages: [],
        }
    },
    components: {
        modal,
    },
    methods: {
        calculate(page = 1) {
            let data = {value: this.valueAmount, years: this.years, percent: this.percent, paymentsInYear: this.paymentsInYear,
                        installmentType: this.installmentType, page: page};
            axios.post('home/calculate', data)
                .then(response => {
                    if (response.data.error !== undefined || response.data.error !== null) {
                        console.log(response.data.error);
                    }
                this.currentPage = page;
                this.totalAmountToPay = response.data.totalAmountToPay.toLocaleString();
                this.installment = response.data.installment;
                this.paymentSchedule = response.data.paymentSchedule;
                this.modalCalculationShow = true;
            });
        },
        choosePage(value, firstOrLastPage = false) {
            console.log(e);
            this.chosenPage = e.target.id;
            //e.target.addClass('chosenPage');
            console.log(e.target.model);
            if (firstOrLastPage) {
                this.calculate(value)
                markPage(value);
            } else {
                this.calculate(this.getPage(value));
                markPage(this.getPage(value));
            }


        },
        getPage(divNumber) {
            switch (this.page) {
                case 1:
                    return divNumber + this.page;
                case 2:
                   switch (divNumber) {
                       case 1:
                           return this.page;
                       case 2:
                           return this.page + 1;
                       case 3:
                           return this.page + 2;
                   }
               case 3:
               case this.years - 2:
                   switch (divNumber) {
                       case 1:
                           return this.page -1 ;
                       case 2:
                           return this.page;
                       case 3:
                           return this.page + 1;
                   }
                case this.years:
                case this.years - 1:
                    switch (divNumber) {
                        case 1:
                            return this.years - 3;
                        case 2:
                            return this.years - 2;
                        case 3:
                            return this.years -1;
                    }
                default:
                    switch (divNumber) {
                        case 1:
                            return this.page - 2;
                        case 2:
                            return this.page -1;
                        case 3:
                            return this.page;
                        case 4:
                            return this.page + 1;
                        case 5:
                            return this.page +2;

                    }
            }
        }
    },
    watch: {
        'chosenPage':function(value) {


        }
    }
}
</script>

<style scoped>
.numerations {
    margin-left: 100px;
    padding-top: 15px;
    padding-bottom: 15px;
}
.active {
    filter: opacity(0.5);
    background-color: lightgray;
}
.numeration_years:hover  {
    cursor: pointer;
}
.numeration_years {
    width: 30px;
    display: inline-block;
    margin-right: 20px;
    border-style: solid;
    border-radius: 4px;
    border-width: thin;
    text-align: center;
    font-weight: bold;
    font-size: small;
    padding-top: 9px;
    height: 23px;
    color: darkblue;
}

@media (max-width: 1040px) {
    .calculateButton {
        margin-top: 30px !important;
        margin-left: 120px;
    }
}

@media (max-width: 492px) {
    .label_input {
        float: left;
        margin-top: 10px;
    }
    .input {
        margin-left: 0px !important;
    }
    .installmentType {
        margin-top: 15px !important;
    }
}

@media (max-width: 850px) {
    .fields {
        margin-bottom: 20px;
    }
    .calculateButton {
        margin-left: 40px !important;
    }

    .installmentType {
        margin-top: 30px !important;
        margin-left: 0px !important;
    }
}
p {
    width: 500px;
    text-align: center;
    font-weight: bold;
    font-size: medium;
}
.interest_capital {
    color: limegreen;
}
.interest_installment {
    color: red;
}
.modal-wait{
    margin-left: 100px;
}
tr {
    font-weight: bold;
    padding: 17px;
    color: #8C8C8D;
    border-bottom: #D2D2D2 1px solid;
    border-top-right-radius: 2px;
    border-top-left-radius: 2px;
    font-size: small;
}
th {
    color: black;
    border-style: none;
    width: 150px;
    text-align: left;
}
td {
    padding-top: 15px;
    width: 150px;
    border-bottom: lightgray;
    border-bottom-style: solid;
    border-bottom-width: thin;
}
.installment_type_describe {
    margin-left: 70px;
}
.fixedInstallment {
    float: left;
    margin-top: 15px;
}
.decreasingInstallment {
    display: inline;
    float: left;
    margin-left: 50px;
    margin-top: 15px;
}
.installmentType {
    width: 300px;
    margin-left: 80px;
    margin-top: 50px;
    display: inline-block;
}
.fields {
    float: left;
}
.field {
    height: 20px;
    margin-top: 20px;
}
.calculation {
    margin-top: 100px;
    height: 800px;
    display: inline;
}
.label_opis {
    width: 160px;
    display: inline-block;
}
.input {
    margin-left: 20px;
    height: 15px;
    display: inline-block;
}

.calculateButton {
    margin-top: 130px;
    width: 150px;
    height: 50px;
    border-radius: 3px;
    color: white;
    background-color: #C63424;
    font-weight: bold;
    font-size: larger;
}
.calculateButton:hover {
    cursor: pointer;
}

</style>