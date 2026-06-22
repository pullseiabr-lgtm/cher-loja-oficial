<template>
    <LoadingComponent :props="loading" />

    <div id="categorySectionCategoryEditModal" class="modal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title">Editar Categoria</h3>
                <button class="modal-close fa-solid fa-xmark text-xl text-slate-400 hover:text-red-500"
                    @click="close"></button>
            </div>
            <div class="modal-body">
                <div class="form-row" v-if="message">
                    <div class="form-col-12 db-field-alert">{{ message }}</div>
                </div>
                <form @submit.prevent="save" class="d-block w-full">
                    <div class="form-row">
                        <div class="form-col-12">
                            <label for="edit_product_category_id" class="db-field-title required">Categoria</label>
                            <vue-select class="db-field-control f-b-custom-select" id="edit_product_category_id"
                                v-bind:class="errors.product_category_id ? 'invalid' : ''"
                                v-model="form.product_category_id"
                                :options="categories" label-by="option" value-by="id"
                                :closeOnSelect="true" :searchable="true" :clearOnClose="true"
                                placeholder="Selecione uma categoria" search-placeholder="Buscar..." />
                            <small class="db-field-alert" v-if="errors.product_category_id">
                                {{ errors.product_category_id[0] }}
                            </small>
                        </div>

                        <div class="form-col-12">
                            <div class="modal-btns">
                                <button type="button" class="modal-btn-outline modal-close" @click="close">
                                    <i class="lab lab-fill-close-circle"></i>
                                    <span>{{ $t("button.close") }}</span>
                                </button>
                                <button type="submit" class="db-btn py-2 text-white bg-primary">
                                    <i class="lab lab-fill-save"></i>
                                    <span>{{ $t("button.save") }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import LoadingComponent from "../../components/LoadingComponent";
import alertService from "../../../../services/alertService";
import appService from "../../../../services/appService";

export default {
    name: "CategorySectionCategoryEditComponent",
    components: { LoadingComponent },
    props: {
        sectionId: { type: Number },
        sectionSearch: { type: Object },
    },
    data() {
        return {
            loading: { isActive: false },
            editingId: null,
            form: {
                product_category_id: null,
            },
            errors: {},
            message: null,
        };
    },
    computed: {
        categories() {
            return this.$store.getters['productCategory/depthTrees'];
        },
    },
    mounted() {
        this.$store.dispatch('productCategory/depthTrees');
    },
    methods: {
        open(item) {
            this.editingId = item.id;
            this.form.product_category_id = item.product_category_id;
            this.errors = {};
            this.message = null;
            appService.modalShow('#categorySectionCategoryEditModal');
        },
        close() {
            appService.modalHide('#categorySectionCategoryEditModal');
            this.errors = {};
            this.message = null;
            this.editingId = null;
        },
        save() {
            try {
                this.loading.isActive = true;
                this.$store.dispatch("categorySectionCategory/update", {
                    categorySection: this.sectionId,
                    id: this.editingId,
                    form: this.form,
                    search: this.sectionSearch,
                }).then(() => {
                    this.loading.isActive = false;
                    appService.modalHide('#categorySectionCategoryEditModal');
                    alertService.successFlip(1, "Categoria");
                    this.editingId = null;
                }).catch((err) => {
                    this.loading.isActive = false;
                    if (err.response?.data?.errors) {
                        this.errors = err.response.data.errors;
                    } else {
                        this.message = err.response?.data?.message ?? null;
                    }
                });
            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err);
            }
        },
    },
};
</script>
