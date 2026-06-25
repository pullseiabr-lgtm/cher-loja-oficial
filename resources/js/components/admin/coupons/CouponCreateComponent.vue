<template>
    <LoadingComponent :props="loading" />
    <SmSidebarModalCreateComponent :props="addButton" />

    <div id="sidebar" @click.self="reset"
        class="fixed inset-0 z-50 bg-black/50 duration-500 transition-all invisible opacity-0 flex items-center justify-center p-4">
        <div class="w-full max-w-2xl max-h-[90vh] bg-white rounded-lg flex flex-col">
        <div class="bg-white border-b border-gray-200 p-4 flex justify-between items-center flex-shrink-0">
            <h3 class="text-lg font-bold">{{ $t("menu.coupons") }}</h3>
            <button class="fa-solid fa-xmark close-btn text-xl text-gray-500 hover:text-gray-700" @click="reset"></button>
        </div>
        <div class="p-6 overflow-y-auto thin-scrolling flex-grow">
            <form ref="couponForm" @submit.prevent="save">
                <div class="form-row">
                    <div class="form-col-12 sm:form-col-6">
                        <label for="name" class="db-field-title required">{{ $t("label.name") }}</label>
                        <input v-model="props.form.name" v-bind:class="errors.name ? 'invalid' : ''" type="text"
                            id="name" class="db-field-control" />
                        <small class="db-field-alert" v-if="errors.name">{{ errors.name[0] }}</small>
                    </div>

                    <div class="form-col-12 sm:form-col-6">
                        <label for="code" class="db-field-title required">{{ $t("label.code") }}</label>
                        <input v-model="props.form.code" v-bind:class="errors.code ? 'invalid' : ''" type="text"
                            id="code" class="db-field-control" />
                        <small class="db-field-alert" v-if="errors.code">{{
                            errors.code[0]
                        }}</small>
                    </div>

                    <div class="form-col-12 sm:form-col-6">
                        <label for="discount" class="db-field-title required">{{
                            $t("label.discount")
                        }}</label>
                        <input v-model="props.form.discount" v-on:keypress="floatNumber($event)"
                            v-bind:class="errors.discount ? 'invalid' : ''" type="text" id="discount"
                            class="db-field-control" />
                        <small class="db-field-alert" v-if="errors.discount">{{ errors.discount[0] }}</small>
                    </div>

                    <div class="form-col-12 sm:form-col-6">
                        <label class="db-field-title required" for="active">{{ $t("label.discount_type") }}</label>
                        <div class="db-field-radio-group">
                            <div class="db-field-radio">
                                <div class="custom-radio">
                                    <input :value="enums.taxTypeEnum.FIXED" v-model="props.form.discount_type"
                                        id="fixed" type="radio" class="custom-radio-field" />
                                    <span class="custom-radio-span"></span>
                                </div>
                                <label for="fixed" class="db-field-label">{{
                                    $t("label.fixed")
                                }}</label>
                            </div>
                            <div class="db-field-radio">
                                <div class="custom-radio">
                                    <input :value="enums.taxTypeEnum.PERCENTAGE" v-model="props.form.discount_type"
                                        type="radio" id="percentage" class="custom-radio-field" />
                                    <span class="custom-radio-span"></span>
                                </div>
                                <label for="percentage" class="db-field-label">{{ $t("label.percentage") }}</label>
                            </div>
                        </div>
                        <small class="db-field-alert" v-if="errors.discount_type">{{ errors.discount_type[0] }}</small>
                    </div>

                    <div class="form-col-12 sm:form-col-6">
                        <label for="start_date" class="db-field-title required">{{ $t("label.start_date") }}</label>
                        <Datepicker hideInputIcon autoApply v-model="props.form.start_date" :enableTimePicker="true" 
                            :is24="false" :monthChangeOnScroll="false" utc="false"
                            :input-class-name="errors.start_date ? 'invalid' : ''">
                            <template #am-pm-button="{ toggle, value }">
                                <button @click="toggle">{{ value }}</button>
                            </template>
                        </Datepicker>
                        <small class="db-field-alert" v-if="errors.start_date">{{ errors.start_date[0] }}</small>
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label for="end_date" class="db-field-title required">{{ $t("label.end_date") }}</label>
                        <Datepicker hideInputIcon autoApply v-model="props.form.end_date" :enableTimePicker="true" 
                            :is24="false" :monthChangeOnScroll="false" utc="false"
                            :input-class-name="errors.end_date ? 'invalid' : ''">
                            <template #am-pm-button="{ toggle, value }">
                                <button @click="toggle">{{ value }}</button>
                            </template>
                        </Datepicker>
                        <small class="db-field-alert" v-if="errors.end_date">{{ errors.end_date[0] }}</small>
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label for="minimum_order" class="db-field-title required">{{
                            $t("label.minimum_order")
                        }}</label>
                        <input v-model="props.form.minimum_order" v-on:keypress="floatNumber($event)"
                            v-bind:class="errors.minimum_order ? 'invalid' : ''" type="text" id="minimum_order"
                            class="db-field-control" />
                        <small class="db-field-alert" v-if="errors.minimum_order">{{ errors.minimum_order[0] }}</small>
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label for="maximum_discount" class="db-field-title required">{{
                            $t("label.maximum_discount")
                        }}</label>
                        <input v-model="props.form.maximum_discount" v-on:keypress="floatNumber($event)"
                            v-bind:class="errors.maximum_discount ? 'invalid' : ''" type="text" id="maximum_discount"
                            class="db-field-control" />
                        <small class="db-field-alert" v-if="errors.maximum_discount">{{
                            errors.maximum_discount[0]
                        }}</small>
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label for="limit_per_user" class="db-field-title">{{ $t("label.limit_per_user") }}</label>
                        <input v-model="props.form.limit_per_user" v-on:keypress="floatNumber($event)" v-bind:class="
                            errors.limit_per_user ? 'invalid' : ''
                        " type="text" id="limit_per_user" class="db-field-control" />
                        <small class="db-field-alert" v-if="errors.limit_per_user">{{
                            errors.limit_per_user[0]
                        }}</small>
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label class="db-field-title">Mostrar no Modal de Descontos</label>
                        <div class="db-field-radio-group">
                            <div class="db-field-radio">
                                <div class="custom-radio">
                                    <input :value="1" v-model="props.form.show_in_modal"
                                        id="show_modal_yes" type="radio" class="custom-radio-field" />
                                    <span class="custom-radio-span"></span>
                                </div>
                                <label for="show_modal_yes" class="db-field-label">Sim</label>
                            </div>
                            <div class="db-field-radio">
                                <div class="custom-radio">
                                    <input :value="0" v-model="props.form.show_in_modal"
                                        type="radio" id="show_modal_no" class="custom-radio-field" />
                                    <span class="custom-radio-span"></span>
                                </div>
                                <label for="show_modal_no" class="db-field-label">Não</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label class="db-field-title">Apenas Primeira Compra</label>
                        <div class="db-field-radio-group">
                            <div class="db-field-radio">
                                <div class="custom-radio">
                                    <input :value="1" v-model="props.form.first_purchase_only"
                                        id="first_purchase_yes" type="radio" class="custom-radio-field" />
                                    <span class="custom-radio-span"></span>
                                </div>
                                <label for="first_purchase_yes" class="db-field-label">Sim</label>
                            </div>
                            <div class="db-field-radio">
                                <div class="custom-radio">
                                    <input :value="0" v-model="props.form.first_purchase_only"
                                        type="radio" id="first_purchase_no" class="custom-radio-field" />
                                    <span class="custom-radio-span"></span>
                                </div>
                                <label for="first_purchase_no" class="db-field-label">Não</label>
                            </div>
                        </div>
                        <small class="text-xs text-gray-400 mt-1 block">Cupom válido apenas para clientes que nunca compraram</small>
                    </div>
                    <div class="form-col-12 sm:form-col-6">
                        <label class="db-field-title required">{{ $t("label.image") }}</label>
                        <input @change="changeImage" v-bind:class="errors.image ? 'invalid' : ''" id="image" type="file"
                            class="db-field-control" ref="imageProperty" accept="image/png, image/jpeg, image/jpg, image/gif" />
                        <small class="db-field-alert" v-if="errors.image">{{ errors.image[0] }}</small>
                        <div v-if="imagePreview" style="margin-top: 12px; border-radius: 8px; overflow: hidden; max-width: 150px;">
                            <img :src="imagePreview" alt="preview" style="width: 100%; height: auto; display: block; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);" />
                        </div>
                    </div>
                    <div class="form-col-12 sm:form-col-12">
                        <label for="description" class="db-field-title">{{
                            $t("label.description")
                        }}</label>
                        <quill-editor v-model:content="props.form.description" contentType="html" :modules="quillModules" class="bg-white" style="border-radius: 8px; min-height: 125px;" />
                        <small class="db-field-alert" v-if="errors.description">{{ errors.description[0] }}</small>
                    </div>
                </div>
            </form>
        </div>
        <div class="bg-white border-t border-gray-200 p-4 flex justify-end gap-3">
            <button type="button" class="modal-btn-outline modal-close" @click="reset">
                <i class="lab lab-fill-close-circle"></i>
                <span>{{ $t("button.close") }}</span>
            </button>
            <button type="button" @click="submitForm" class="db-btn py-2 text-white bg-primary">
                <i class="lab lab-fill-save"></i>
                <span>{{ $t("label.save") }}</span>
            </button>
        </div>
        </div>
    </div>
</template>
<script>
import SmSidebarModalCreateComponent from "../components/buttons/SmSidebarModalCreateComponent";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import LoadingComponent from "../components/LoadingComponent";
import taxTypeEnum from "../../../enums/modules/taxTypeEnum";
import alertService from "../../../services/alertService";
import appService from "../../../services/appService";
import { useCanvas } from "../../../composables/canvas";
import { quillEditor } from 'vue3-quill';
import 'quill/dist/quill.core.css';
import 'quill/dist/quill.snow.css';

export default {
    name: "CouponCreateComponent",
    components: { SmSidebarModalCreateComponent, LoadingComponent, Datepicker, quillEditor },
    props: ["props"],
    data() {
        return {
            loading: {
                isActive: false,
            },
            enums: {
                taxTypeEnum: taxTypeEnum,
                taxTypeEnumArray: {
                    [taxTypeEnum.FIXED]: this.$t("label.fixed"),
                    [taxTypeEnum.PERCENTAGE]: this.$t("label.percentage"),
                },
            },
            image: "",
            imagePreview: "",
            errors: {},
            quillModules: {
                toolbar: [
                    ['bold', 'italic', 'underline', 'strike'],
                    ['blockquote', 'code-block'],
                    [{ 'header': 1 }, { 'header': 2 }],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'script': 'sub'}, { 'script': 'super' }],
                    [{ 'indent': '-1'}, { 'indent': '+1' }],
                    [{ 'size': ['small', false, 'large', 'huge'] }],
                    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'align': [] }],
                    ['clean']
                ]
            }
        };
    },
    computed: {
        addButton: function () {
              return {title: this.$t("button.add_coupon")}
        }
    },
    methods: {
        floatNumber(e) {
            return appService.floatNumber(e);
        },
        changeImage: function (e) {
            this.image = e.target.files[0];
            if (this.image) {
                const reader = new FileReader();
                reader.onload = (event) => {
                    this.imagePreview = event.target.result;
                };
                reader.readAsDataURL(this.image);
            } else {
                this.imagePreview = "";
            }
        },
        reset: function () {
            useCanvas().closeCanvas('sidebar');
            this.$store.dispatch("coupon/reset").then().catch();
            this.errors = {};
            this.$props.props.form = {
                name: "",
                description: "",
                code: "",
                discount: "",
                discount_type: taxTypeEnum.FIXED,
                start_date: "",
                end_date: "",
                minimum_order: "",
                maximum_discount: "",
                limit_per_user: "",
                show_in_modal: 1,
                first_purchase_only: 0,
            };
            if (this.image) {
                this.image = "";
                this.imagePreview = "";
                this.$refs.imageProperty.value = null;
            }
        },

        save: function () {
            try {
                const fd = new FormData();
                fd.append("name", this.props.form.name);
                fd.append("description", this.props.form.description);
                fd.append("code", this.props.form.code);
                fd.append("discount", this.props.form.discount);
                fd.append("discount_type", this.props.form.discount_type);
                fd.append("start_date", this.props.form.start_date);
                fd.append("end_date", this.props.form.end_date);
                fd.append("minimum_order", this.props.form.minimum_order);
                fd.append("maximum_discount", this.props.form.maximum_discount);
                fd.append("limit_per_user", this.props.form.limit_per_user);
                fd.append("show_in_modal", this.props.form.show_in_modal);
                fd.append("first_purchase_only", this.props.form.first_purchase_only);
                if (this.image) {
                    fd.append("image", this.image);
                }
                const tempId = this.$store.getters["coupon/temp"].temp_id;
                this.loading.isActive = true;
                this.$store
                    .dispatch("coupon/save", {
                        form: fd,
                        search: this.props.search,
                    })
                    .then((res) => {
                        useCanvas().closeCanvas('sidebar');
                        this.loading.isActive = false;
                        alertService.successFlip(
                            tempId === null ? 0 : 1,
                            this.$t("menu.coupons")
                        );
                        this.props.form = {
                            name: "",
                            description: "",
                            code: "",
                            discount: "",
                            discount_type: taxTypeEnum.FIXED,
                            start_date: "",
                            end_date: "",
                            minimum_order: "",
                            maximum_discount: "",
                            limit_per_user: "",
                            show_in_modal: 1,
                            first_purchase_only: 0,
                        };
                        this.image = "";
                        this.imagePreview = "";
                        this.errors = {};
                        this.$refs.imageProperty.value = null;
                    })
                    .catch((err) => {
                        this.loading.isActive = false;
                        this.errors = err.response.data.errors;
                    });
            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err);
            }
        },
        submitForm: function () {
            this.$refs.couponForm.dispatchEvent(new Event('submit'));
        }
    },
};
</script>
