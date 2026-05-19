<template>
    <LoadingComponent :props="loading" />
    <SmSidebarModalCreateComponent :props="addButton" />

    <div id="sidebar" @click.self="reset"
        class="fixed inset-0 z-50 bg-black/50 duration-500 transition-all invisible opacity-0">
        <div class="w-full max-w-xl h-screen overflow-x-hidden thin-scrolling bg-white ms-auto ltr:translate-x-full rtl:-translate-x-full">
            <div class="drawer-header">
                <h3 class="drawer-title">{{ temp.isEditing ? 'Editar Menu' : 'Novo Menu' }}</h3>
                <button class="fa-solid fa-xmark close-btn" @click="reset"></button>
            </div>
            <div class="drawer-body">
                <form @submit.prevent="save">
                    <div class="form-row">
                        <!-- Name -->
                        <div class="form-col-12 sm:form-col-6">
                            <label for="menuName" class="db-field-title required">{{ $t('label.name') }}</label>
                            <input v-model="props.form.name" :class="errors.name ? 'invalid' : ''"
                                type="text" id="menuName" class="db-field-control" />
                            <small class="db-field-alert" v-if="errors.name">{{ errors.name[0] }}</small>
                        </div>

                        <!-- Location -->
                        <div class="form-col-12 sm:form-col-6">
                            <label for="menuLocation" class="db-field-title required">Local</label>
                            <select v-model="props.form.location" id="menuLocation"
                                :class="errors.location ? 'invalid' : ''" class="db-field-control">
                                <option value="header">Header (Cabeçalho)</option>
                                <option value="footer">Footer (Rodapé)</option>
                            </select>
                            <small class="db-field-alert" v-if="errors.location">{{ errors.location[0] }}</small>
                        </div>

                        <!-- Status -->
                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title required">{{ $t('label.status') }}</label>
                            <div class="db-field-radio-group">
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input type="radio" v-model="props.form.status" id="statusActive"
                                            :value="1" class="custom-radio-field" />
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="statusActive" class="db-field-label">{{ $t('label.active') }}</label>
                                </div>
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input type="radio" v-model="props.form.status" id="statusInactive"
                                            :value="0" class="custom-radio-field" />
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="statusInactive" class="db-field-label">{{ $t('label.inactive') }}</label>
                                </div>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="form-col-12">
                            <div class="flex flex-wrap gap-3 mt-4">
                                <button type="submit" class="db-btn py-2 text-white bg-primary">
                                    <i class="lab lab-fill-save"></i>
                                    <span>{{ $t('label.save') }}</span>
                                </button>
                                <button type="button" class="modal-btn-outline modal-close" @click="reset">
                                    <i class="lab lab-fill-close-circle"></i>
                                    <span>{{ $t('button.close') }}</span>
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
import alertService from "../../../services/alertService";
import appService from "../../../services/appService";
import { useCanvas } from "../../../composables/canvas";

export default {
    name: "SiteMenuCreateComponent",
    components: { SmSidebarModalCreateComponent, LoadingComponent },
    props: ["props"],
    data() {
        return {
            loading: { isActive: false },
            errors: {},
        };
    },
    computed: {
        addButton() { return { title: "Adicionar Menu" }; },
        temp()      { return this.$store.getters['siteMenu/temp']; },
    },
    methods: {
        reset() {
            useCanvas().closeCanvas('sidebar');
            this.$store.dispatch('siteMenu/reset');
            this.errors = {};
            this.$props.props.form = { name: '', location: 'header', status: 1 };
        },
        save() {
            try {
                const tempId = this.$store.getters['siteMenu/temp'].temp_id;
                this.loading.isActive = true;
                this.$store.dispatch('siteMenu/save', {
                    form: this.props.form,
                    search: this.props.search,
                }).then(() => {
                    useCanvas().closeCanvas('sidebar');
                    this.loading.isActive = false;
                    alertService.successFlip(tempId === null ? 0 : 1, 'Menu');
                    this.props.form = { name: '', location: 'header', status: 1 };
                    this.errors = {};
                }).catch((err) => {
                    this.loading.isActive = false;
                    this.errors = err.response?.data?.errors || {};
                });
            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err);
            }
        },
    },
};
</script>
