<template>
    <div class="form-wrapper container" style="width: 50%">
        <Form v-show="!show.tokenExist" @submit="initializeToken" style="border: 1px solid #ccc; padding: 20px;">
            <div class="mb-3">
                <label class="form-label"><strong>{{$t('stage_0')}}</strong></label>
            </div>
            <div class="alert alert-warning" role="alert" v-html="$t('missing_token')"></div>
            <div class="row">
                <div class="col-md-12">
                    <label class="form-label">{{$t('stage_0_client_id')}}</label>
                    <Field type="text" name="client_id" rules="required"  v-model="input.clientId" class="form-control" :placeholder="$t('stage_0_client_id_placeholder')"/>
                    <ErrorMessage name="client_id" style="color: red"/>
                </div>

                <div class="col-md-12">
                    <label class="form-label">{{$t('stage_0_client_secret')}}</label>
                    <Field type="text" name="client_secret"  rules="required" class="form-control" :placeholder="$t('stage_0_client_secret_placeholder')"/>
                    <ErrorMessage name="client_secret" style="color: red"/>
                </div>

                <div class="col-md-12">
                    <label class="form-label">{{$t('stage_0_redirect_uri')}}</label>
                    <Field type="text" name="redirect_uri" v-model="input.redirectUri" rules="required" class="form-control" :placeholder="$t('stage_0_redirect_uri_placeholder')"/>
                    <ErrorMessage name="redirect_uri" style="color: red"/>
                </div>

                <div class="col-md-12">
                    <label class="form-label">
                        {{$t('stage_0_code')}}
                        <a v-show="show.codelink"  :href="apiGetCode" target="_blank">{{ $t('explain_token_code_getting') }}</a>
                    </label>
                    <Field type="text" name="code"  rules="required" class="form-control" @focus="show.codelink = true" :placeholder="$t('stage_0_code_placeholder')"/>
                    <ErrorMessage name="code" style="color: red"/>
                </div>

                <div class="col-md-12">
                    <label class="form-label">{{$t('Organization Id')}}</label>
                    <Field type="text" name="organization_id"  rules="required" class="form-control" :placeholder="$t('get from zoho inventory')"/>
                    <ErrorMessage name="organization_id" style="color: red"/>
                </div>

                <Field type="hidden" name="grant_type" value="authorization_code"/>

                <div class="col-12 mt-3">
                    <button type="submit" class="btn btn-success">{{$t('form_add')}}</button>
                </div>
            </div>
        </Form>


        <Form v-show="show.tokenExist" @submit="createOrder" style="border: 1px solid #ccc; padding: 20px;">
            <div class="mb-3">
                <label class="form-label"><strong>{{$t('Create Sales Order')}}</strong></label>
            </div>
            <div class="row">

                <div class="col-md-6">
                    <label class="form-label">{{$t('Select Customer')}}</label>
                    <Field name="customer_id" >
                        <select v-model="selected.contact" class="form-select form-select-sm mb-3" aria-label=".form-select-lg example" name="contact_name">
                            <option v-for="contact in arrays.contacts" :value="contact.contact_id" :key="contact.contact_id" >{{ contact.contact_name }}</option>
                        </select>
                        <ErrorMessage name="contact_name" style="color: red"/>
                        <div class="form-text"><button type="button" class="btn btn-warning btn-sm"  data-bs-toggle="modal" data-bs-target="#staticBackdrop">{{ $t('Or Add new customer')}}</button></div>


                        <!-- Modal -->
                        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Customer name</label>
                                            <input type="name" class="form-control contact_name"  placeholder="add new customer">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary" @click="createContact">{{ $t('Create') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>






                    </Field>
                </div>


                <div class="col-md-6">
                    <label class="form-label">{{$t('Select Items')}}</label>
                    <Field name="line_items" >
                        <select v-model="selected.lineItem" class="form-select form-select-sm mb-3" aria-label=".form-select-lg example" name="line_items" @click="appendRow">
                            <option v-for="line_item in arrays.line_items" :value="line_item" :key="line_item.item_id">{{ line_item.name }}</option>
                        </select>
                        <ErrorMessage name="line_items" style="color: red"/>
                    </Field>
                </div>


                <div class="col-md-6">
                    <label class="form-label">{{$t('Date Added')}}</label>
                    <Field type="date" class="form-control" name="date" rules="required"/>
                    <ErrorMessage name="date" style="color: red"/>
                </div>


                <div v-show="show.order" class="mt-3">

                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th scope="col">{{ $t('name') }}</th>
                            <th scope="col">{{ $t('rate') }}</th>
                            <th scope="col">{{ $t('quantity') }}</th>
                            <th scope="col">{{ $t('action') }}</th>
                        </tr>
                        </thead>
                        <tbody class="sales-order">

                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-12 mt-4">
                <button v-show="!show.purchaice" type="submit" class="btn btn-success" >{{$t('Create order')}}</button>
            </div>
        </Form>


        <Form v-show="show.purchaice" @submit="createPurchaice" style="border: 1px solid #ccc; padding: 20px;">
            <div class="col-12 mt-3">
                <div class="alert alert-warning purchaice-message" role="alert" v-html="$t('Sales order created! Some items is out stock. Please send Purchaice request to we can send order')"></div>
            </div>

            <div class="col-md-6">
                <label class="form-label">{{$t('Select vendor')}}</label>
                <Field name="vendor_id" >
                    <select v-model="selected.vendor" class="form-select form-select-sm mb-3" aria-label=".form-select-lg example" name="vendor_id">
                        <option v-for="vendor in arrays.vendors" :value="vendor" :key="vendor.contact_id">{{ vendor.contact_name }}</option>
                    </select>
                    <ErrorMessage name="vendor_id" style="color: red"/>
                </Field>
            </div>


            <div>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th scope="col">{{ $t('name') }}</th>
                        <th scope="col">{{ $t('qty on stock') }}</th>
                        <th scope="col">{{ $t('requested qty') }}</th>
                    </tr>
                    </thead>
                    <tbody class="purchaice">

                    </tbody>
                </table>
            </div>

            <button :disabled='disable.purchaice' type="submit" class="btn btn-warning" >{{$t('Create purchaice')}}</button>
        </Form>
    </div>
</template>


<script>
    import axios from 'axios';
    import $ from 'jquery';
    import { Form, Field, ErrorMessage, defineRule } from 'vee-validate';

   // import IMask from 'imask';
    import Swal from 'sweetalert2';

    defineRule('required', value => {
        if (!value || !value.length) {
            return 'validation.required';
        }
        return true;
    });

    defineRule('name', value => {
        const usernamePattern = /^[A-Za-zа-яА-ЯёЁ0-9 ]*$/;

        if (!usernamePattern.test(value)) {
            return 'field has invalid format';
        }
        return true;
    });


    export default {

        data() {

            return {
                selected: {
                    contact: '',
                    lineItem: '',
                    vendor: '',
                },

                show: {
                    purchaice: false,
                    order: false,
                    codelink: false,
                    tokenExist: false
                },

                disable: {
                    purchaice: false,
                },

                input: {
                    clientId: '',
                    redirectUri: '',
                },

                arrays: {
                    vendors: [],
                    contacts: [],
                    line_items: [],
                },

                purchaice: {
                    order_id: '',
                },
            }
        },

        mounted() {

            this.initialize();
        },

        components: {
            Form,
            Field,
            ErrorMessage,
        },

        computed: {

            apiGetCode(){
                /**
                 * @var scope needs to manage ZOHO API
                 * @type {string}
                 */
                const scope = "ZohoInventory.FullAccess.all," +
                              "ZohoInventory.invoices.All&";


                return 'https://accounts.zoho.eu/oauth/v2/auth?' +
                    'client_id=' + this.input.clientId+
                    '&response_type=code'+
                    '&scope=' + scope +
                    '&redirect_uri='+ this.input.redirectUri +
                    '&access_type=offline'+
                    '&prompt=consent';
            },
        },

        methods: {

            async initialize(){
                var self = this;


                try {
                    await axios.get('http://127.0.0.1/api/v2/zoho/check_token')
                        .then((response) => {
                            self.show.tokenExist = new Boolean(response.data).valueOf();

                            if(self.show.tokenExist){
                                self.getContacts();
                                self.getLineItems();
                                self.getVendors();
                            }
                        });

                    //this.mask = IMask(document.getElementById('phone'), {mask: '+{38} (000) 000-00-00'});
                } catch (error) {
                    Swal.fire({
                        title: 'Ошибка при загрузке данных: /api/v2/zoho/check_token',
                        text: error,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            },


            async initializeToken(formData){
                var self = this;
                try {
                    await axios.post('http://127.0.0.1/api/v2/zoho/create_token', formData)
                        .then((response) => {
                            self.show.tokenExist = new Boolean(response.data).valueOf();

                            if(self.show.tokenExist){
                                self.getContacts();
                                self.getLineItems();
                                self.getVendors();
                            }
                        });
                } catch (error) {
                    Swal.fire({
                        title: 'Ошибка при загрузке данных: /api/v2/zoho/init_token',
                        text: error,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            },


            async getContacts(){
                try {
                    const response = await axios.get('http://127.0.0.1/api/v2/zoho/get_contacts');
                    this.arrays.contacts = response.data;
                } catch (error) {
                    Swal.fire({
                        title: 'Ошибка при загрузке данных: /api/v2/zoho/get_contacts',
                        text: error,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            },


            async getVendors(){
                try {
                    const response = await axios.get('http://127.0.0.1/api/v2/zoho/get_vendors');
                    this.arrays.vendors = response.data;
                } catch (error) {
                    Swal.fire({
                        title: 'Ошибка при загрузке данных: /api/v2/zoho/get_vendors',
                        text: error,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            },


            async getLineItems(){
                try {
                    const response = await axios.get('http://127.0.0.1/api/v2/zoho/get_line_items');
                    this.arrays.line_items = response.data;
                } catch (error) {
                    Swal.fire({
                        title: 'Ошибка при загрузке данных: /api/v2/zoho/get_line_items',
                        text: error,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            },


            async createOrder(formData){

                formData.customer_id = this.selected.contact;
                formData.line_items = [];
                var vary = this.arrays.line_items;
                var out_stacks = [];
                var self = this;



                $( ".sales-order > tr" ).each(function( index, element ) {
                    let id              = element.dataset.itemId;
                    let requested_qty   = $(element).find('input').val();
                    let exists_on_stock = vary[id].stock_on_hand;

                    if(exists_on_stock < requested_qty){

                        self.show.purchaice = true;


                        var stock_item = out_stacks[id];

                        if(stock_item){
                            var last_requested_qty = stock_item.requested_qty;
                            stock_item.requested_qty = parseInt(last_requested_qty) + parseInt(requested_qty);
                        }else{
                            stock_item = {};
                            stock_item.requested_qty = parseInt(requested_qty) - parseInt(exists_on_stock);
                            stock_item.qty = exists_on_stock;
                            stock_item.name = vary[id].name;
                            stock_item.id = id;
                        }

                        out_stacks[id] = stock_item;
                    }


                    formData.line_items.push({
                        "item_id": id,
                        "quantity": parseFloat(requested_qty),
                    });



                });


                try {
                    await axios.post('http://127.0.0.1/api/v2/zoho/salesorders', formData)
                        .then(() => {
                            Swal.fire({
                                title: 'order placed successfully!',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            });


                            self.appendPurchaiceRow(out_stacks);
                        });

                } catch (error) {
                    Swal.fire({
                        title: 'Ошибка при загрузке данных: /api/v2/zoho/salesorders',
                        text: error,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            },


            async createPurchaice(formData){
                formData.vendor_id = this.selected.vendor.contact_id;
                formData.line_items = [];

                $( ".purchaice > tr" ).each(function( index, element ) {
                    let id              = element.dataset.itemId;
                    let requested_qty   = $(element).find('input').val();

                    formData.line_items.push({
                        "item_id": id,
                        "quantity": parseFloat(requested_qty),
                    });
                });


                try {
                    await axios.post('http://127.0.0.1/api/v2/zoho/create_purchaice', formData)
                        .then(() => {
                            this.disable.purchaice = true;
                            $('.purchaice-message').html("Purchaice order placed!")
                        });
                } catch (error) {
                    Swal.fire({
                        title: 'Ошибка при загрузке данных: /api/v2/zoho/create_purchaice',
                        text: error,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            },


            async createContact(){
                var formData = {};
                var self = this;

                formData.contact_name = $('.contact_name').val();

                try {
                    await axios.post('http://127.0.0.1/api/v2/zoho/contacts', formData)
                        .then(() => {
                            Swal.fire({
                                title: 'customer placed successfully!',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            });
                        })
                        .then(
                            await self.getContacts()
                        );


                } catch (error) {
                    Swal.fire({
                        title: 'Ошибка при загрузке данных: /api/v2/zoho/contacts',
                        text: error,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            },


            appendRow(){
                this.show.order = true;


                let template = "" +
                    "<tr data-item-id='prod'>" +
                        "<td>name</td>" +
                        "<td>rate</td>" +
                        "<td>" +
                            "<input type='number' value='1' min='1'/>" +
                        "</td>" +
                        "<td>" +
                            "<button type='button' class='btn btn-danger' onclick=$(this).closest('tr').empty();> - </button>" +
                        "</td>" +
                    "</tr>";


                template = template.replace('prod', this.selected.lineItem.item_id);
                template = template.replace('name', this.selected.lineItem.name);
                template = template.replace('rate', this.selected.lineItem.rate);
                template = template.replace('clean', '$(this).closest(\'tr\').empty();');

                $('.sales-order').append(template);
            },


            appendPurchaiceRow(array){
                this.show.purchaice = true;

                let html = "";

                $.each(this.arrays.line_items, function( value ) {

                    let item = array[value];

                    if(item){
                        html += "<tr data-item-id='"+item.id +"'>" +
                                    "<td>"+item.name +"</td>" +
                                    "<td>"+item.qty +"</td>" +
                                    "<td>" +
                                        "<input type='number' value='"+item.requested_qty+"' min='"+item.requested_qty+"'/>" +
                                    "</td>" +
                                "</tr>";
                    }
                });


                $('.purchaice').append(html);
            },

        },


    }
</script>
