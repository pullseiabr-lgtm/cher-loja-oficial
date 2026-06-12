<template>
    <LoadingComponent :props="loading" />
    <SmSidebarModalCreateComponent :props="addButton" />

    <div id="sidebar" @click.self="reset"
        class="fixed inset-0 z-50 bg-black/50 duration-500 transition-all invisible opacity-0">
        <div class="w-full max-w-xl h-screen overflow-x-hidden thin-scrolling bg-white ms-auto ltr:translate-x-full rtl:-translate-x-full">
            <div class="drawer-header">
                <h3 class="drawer-title">Seção de Promoções</h3>
                <button class="fa-solid fa-xmark close-btn" @click="reset"></button>
            </div>
            <div class="drawer-body">
                <form @submit.prevent="save">
                    <div class="form-row">
                        <div class="form-col-12 sm:form-col-6">
                            <label for="name" class="db-field-title required">{{ $t("label.name") }}</label>
                            <input v-model="props.form.name" v-bind:class="errors.name ? 'invalid' : ''"
                                type="text" id="name" class="db-field-control" />
                            <small class="db-field-alert" v-if="errors.name">{{ errors.name[0] }}</small>
                        </div>

                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title required">{{ $t("label.status") }}</label>
                            <div class="db-field-radio-group">
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input type="radio" v-model="props.form.status" id="statusActive"
                                            :value="enums.statusEnum.ACTIVE" class="custom-radio-field" />
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="statusActive" class="db-field-label">{{ $t("label.active") }}</label>
                                </div>
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input type="radio" class="custom-radio-field" v-model="props.form.status"
                                            id="statusInactive" :value="enums.statusEnum.INACTIVE" />
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="statusInactive" class="db-field-label">{{ $t("label.inactive") }}</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title required">Layout</label>
                            <div class="db-field-radio-group">
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input type="radio" v-model="props.form.layout_type" id="layoutGrid"
                                            value="grid" class="custom-radio-field" />
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="layoutGrid" class="db-field-label">Grade</label>
                                </div>
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input type="radio" class="custom-radio-field" v-model="props.form.layout_type"
                                            id="layoutCarousel" value="carousel" />
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="layoutCarousel" class="db-field-label">Carrossel</label>
                                </div>
                            </div>
                            <small class="db-field-alert" v-if="errors.layout_type">{{ errors.layout_type[0] }}</small>
                        </div>

                        <div class="form-col-12 sm:form-col-6" v-if="props.form.layout_type === 'grid'">
                            <label for="columns" class="db-field-title">Colunas por linha</label>
                            <select v-model="props.form.columns" id="columns" class="db-field-control">
                                <option :value="null">— Auto —</option>
                                <option v-for="n in 6" :key="n" :value="n">{{ n }}</option>
                            </select>
                            <small class="db-field-alert" v-if="errors.columns">{{ errors.columns[0] }}</small>
                        </div>

                        <div class="form-col-12">
                            <div class="flex flex-wrap gap-3 mt-4">
                                <button type="submit" class="db-btn py-2 text-white bg-primary">
                                    <i class="lab lab-fill-save"></i>
                                    <span>{{ $t("label.save") }}</span>
                                </button>
                                <button type="button" class="modal-btn-outline modal-close" @click="reset">
                                    <i class="lab lab-fill-close-circle"></i>
                                    <span>{{ $t("button.close") }}</span>
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
import SmSidebarModalCreateComponent from "../components/buttons/SmSidebarModalCreateComponent";
import LoadingComponent from "../components/LoadingComponent";
import statusEnum from "../../../enums/modules/statusEnum";
import alertService from "../../../services/alertService";
import { useCanvas } from "../../../composables/canvas";

export default {
    name: "PromotionSectionCreateComponent",
    components: { SmSidebarModalCreateComponent, LoadingComponent },
    props: ["props"],
    data() {
        return {
            loading: { isActive: false },
            enums: {
                statusEnum: statusEnum,
            },
            errors: {},
        };
    },
    computed: {
        addButton: function () {
            return { title: "Adicionar Seção" };
        }
    },
    methods: {
        reset: function () {
            useCanvas().closeCanvas('sidebar');
            this.$store.dispatch("promotionSection/reset").then().catch();
            this.errors = {};
            this.$props.props.form = {
                name: "",
                layout_type: "grid",
                columns: null,
                status: statusEnum.ACTIVE,
            };
        },
        save: function () {
            try {
                const tempId = this.$store.getters["promotionSection/temp"].temp_id;
                this.loading.isActive = true;
                this.$store.dispatch("promotionSection/save", {
                    form: this.props.form,
                    search: this.props.search,
                }).then(() => {
                    useCanvas().closeCanvas('sidebar');
                    this.loading.isActive = false;
                    alertService.successFlip(tempId === null ? 0 : 1, "Seção de Promoções");
                    this.props.form = { name: "", layout_type: "grid", columns: null, status: statusEnum.ACTIVE };
                    this.errors = {};
                }).catch((err) => {
                    this.loading.isActive = false;
                    this.errors = err.response.data.errors;
                });
            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err);
            }
        },
    },
};
</script>
