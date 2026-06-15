<template>
    <LoadingComponent :props="loading" />

    <div class="col-12">
        <div id="product" class="db-tab-div active">
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 mb-5">

                <button @click.prevent="multiTargets($event, 'tab-action', 'tab-content', 'information')"
                    class="tab-action active w-full flex items-center gap-3 h-10 px-4 rounded-lg bg-white hover:text-primary hover:bg-primary/5">
                    <i class="lab lab-fill-info lab-font-size-16"></i>
                    {{ $t("label.information") }}
                </button>
                <button type="button" @click.prevent="multiTargets($event, 'tab-action', 'tab-content', 'image')"
                    class="tab-action w-full flex items-center gap-3 h-10 px-4 rounded-lg transition bg-white hover:text-primary hover:bg-primary/5">
                    <i class="lab lab-fill-image lab-font-size-16"></i>
                    {{ $t("label.images") }}
                </button>
                <button type="button" @click.prevent="multiTargets($event, 'tab-action', 'tab-content', 'variations')"
                    class="tab-action w-full flex items-center gap-3 h-10 px-4 rounded-lg transition bg-white hover:text-primary hover:bg-primary/5">
                    <i class="lab lab-fill-variation lab-font-size-16"></i>
                    {{ $t("label.variation") }}
                </button>


                <div class="relative w-full dropdown-group">
                    <button @click.prevent="tabMore = !tabMore"
                        class="dropdown-btn tab-action w-full flex items-center gap-3 h-10 px-4 rounded-lg bg-white hover:text-primary hover:bg-primary/5">
                        <i :class="{ 'rotate-180': tabMore }"
                            class="lab lab-fill-circle-chevron-down text-sm transition-all duration-500"></i>
                        <span class="capitalize tracking-wide">{{ $t('label.more') }}</span>
                    </button>
                    <!-- :class="{ 'scale-y-100': tabMore == true, 'scale-y-0': tabMore == false }" -->
                    <div 
                        class="dropdown-list absolute top-11 right-0 w-full z-30 p-2 rounded-md origin-top bg-white shadow-lg transition-all duration-500">
                        <button type="button"
                            class="tab-action w-full flex items-center gap-3 h-10 px-4 rounded-lg transition bg-white hover:text-primary hover:bg-primary/5"
                            @click.prevent="multiTargets($event, 'tab-action', 'tab-content', 'barcode')">
                            <i class="lab lab-fill-scan-barcode lab-font-size-16"></i>
                            {{ $t("label.barcode") }}
                        </button>
                        <button type="button"
                            @click.prevent="multiTargets($event, 'tab-action', 'tab-content', 'offer')"
                            class="tab-action w-full flex items-center gap-3 h-10 px-4 rounded-lg transition bg-white hover:text-primary hover:bg-primary/5">
                            <i class="lab lab-fill-offers lab-font-size-16"></i>
                            {{ $t("label.offer") }}
                        </button>
                        <button type="button"
                            class="tab-action w-full flex items-center gap-3 h-10 px-4 rounded-lg transition bg-white hover:text-primary hover:bg-primary/5"
                            @click.prevent="multiTargets($event, 'tab-action', 'tab-content', 'video')">
                            <i class="lab lab-fill-video lab-font-size-16"></i>
                            {{ $t("label.video") }}
                        </button>
                        <button type="button"
                            @click.prevent="multiTargets($event, 'tab-action', 'tab-content', 'shippingReturn')"
                            class="tab-action w-full flex items-center gap-3 h-10 px-4 rounded-lg transition bg-white hover:text-primary hover:bg-primary/5">
                            <i class="lab lab-fill-shipping lab-font-size-16"></i>
                            {{ $t("label.shipping_and_return") }}
                        </button>
                        <button type="button" @click.prevent="multiTargets($event, 'tab-action', 'tab-content', 'seo')"
                            class="tab-action w-full flex items-center gap-3 h-10 px-4 rounded-lg transition bg-white hover:text-primary hover:bg-primary/5">
                            <i class="lab lab-fill-seo lab-font-size-16"></i>
                            {{ $t("label.seo") }}
                        </button>
                    </div>
                </div>
            </div>

            <div class="db-card tab-content active" id="information">
                <div class="db-card-header">
                    <h3 class="db-card-title">{{ $t('label.information') }}</h3>
                </div>
                <div class="db-card-body">
                    <div class="row py-2">
                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ $t("label.name") }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ product.name }}</span>
                            </div>
                        </div>

                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ $t("label.sku") }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ product.sku }}</span>
                            </div>
                        </div>

                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ $t("label.category") }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ product.category }}</span>
                            </div>
                        </div>

                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ $t("label.barcode") }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ product.barcode }}</span>
                            </div>
                        </div>

                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ $t("label.brand") }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ product.brand }}</span>
                            </div>
                        </div>

                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ $t("label.tax") }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ product.tax }}</span>
                            </div>
                        </div>

                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ $t("label.buying_price") }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ product.flat_buying_price }}</span>
                            </div>
                        </div>

                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ $t("label.selling_price") }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ product.flat_selling_price }}</span>
                            </div>
                        </div>

                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{
                                    $t("label.maximum_purchase_quantity")
                                    }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ product.maximum_purchase_quantity
                                    }}</span>
                            </div>
                        </div>
                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ $t("label.warranty") }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ product.warranty }}</span>
                            </div>
                        </div>
                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{
                                    $t("label.low_stock_quantity_warning")
                                    }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ product.low_stock_quantity_warning
                                    }}</span>
                            </div>
                        </div>

                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ $t("label.weight") }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ product.weight }}</span>
                            </div>
                        </div>

                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ $t("label.unit") }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ product.unit }}</span>
                            </div>
                        </div>

                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ $t("label.purchasable") }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{
                                    enums.askEnumArray[product.can_purchasable]
                                    }}</span>
                            </div>
                        </div>

                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ $t("label.show_stock_out") }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{
                                    enums.activityEnumArray[product.show_stock_out]
                                    }}</span>
                            </div>
                        </div>

                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ $t("label.refundable") }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{
                                    enums.askEnumArray[product.refundable]
                                    }}</span>
                            </div>
                        </div>

                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ $t("label.status") }}</span>
                                <span class="db-list-item-text">{{ enums.statusEnumArray[product.status] }}</span>
                            </div>
                        </div>

                        <div class="col-12 sm:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ $t("label.tags") }}</span>
                                <span class="db-list-item-text">{{ product.tags }}</span>
                            </div>
                        </div>

                        <div class="col-12 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-text mt-2 w-full">
                                    <span class="mt-2 db-list-item-title">{{ $t("label.description") }}</span><br />
                                    <span class="mt-2" v-html="product.description"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="db-card tab-content px-4" id="image">
                <div class="row py-4 p-3">
                    <div class="w-full flex flex-col gap-6">

                        <!-- Image gallery -->
                        <div class="w-full max-w-[640px] flex flex-col-reverse sm:flex-row gap-3 sm:gap-5">

                            <!-- Thumbnails sidebar -->
                            <nav class="flex-shrink-0 w-full sm:max-w-[90px] flex flex-row sm:flex-col gap-3">
                                <label for="addImage" v-if="product.images && product.images.length < 6"
                                    class="relative w-full h-16 sm:h-20 flex items-center justify-center rounded-2xl cursor-pointer text-heading bg-gray-200 hover:bg-gray-300 transition">
                                    <input type="file" id="addImage" @change="saveImage" ref="imageProperty"
                                        class="w-full h-full absolute -z-10 rounded-2xl opacity-0"
                                        accept="image/png, image/jpeg, image/jpg">
                                    <i class="lab-fill-circle-plus text-xl sm:text-3xl"></i>
                                </label>

                                <!-- Each thumbnail — only switches the preview -->
                                <div v-for="(image, index) in product.images" :key="index"
                                    class="relative w-full cursor-pointer"
                                    @click.prevent="switchImage(image, index)">
                                    <img class="w-full h-16 sm:h-20 object-cover object-top rounded-2xl transition"
                                        :class="deleteIndex === index ? 'ring-2 ring-primary' : 'ring-1 ring-gray-200'"
                                        :src="image" alt="product" />

                                    <!-- Cover star badge (index 0 = current cover) -->
                                    <span v-if="index === 0"
                                        class="absolute top-1 left-1 w-5 h-5 rounded-full bg-primary flex items-center justify-center shadow"
                                        title="Imagem de capa atual">
                                        <i class="fa-solid fa-star text-white text-[9px]"></i>
                                    </span>
                                </div>
                            </nav>

                            <!-- Main preview -->
                            <div class="w-full relative" v-if="livePreview">
                                <div class="w-full h-96 sm:h-[480px] rounded-2xl overflow-hidden">
                                    <img class="w-full h-full object-cover transition-all duration-300"
                                        :style="{ objectPosition: coverPosition, transform: `scale(${coverZoom})`, transformOrigin: coverPosition }"
                                        alt="products" :src="livePreview" />
                                </div>
                                <button v-if="imageCount > 0" @click.prevent="deleteImage"
                                    class="lab-line-cross text-3xl absolute -top-3 -right-3 w-9 h-9 leading-9 text-center rounded-full shadow-md bg-white text-danger"
                                    type="button"></button>
                            </div>
                        </div>

                        <!-- Controls row: Square preview + Position grid + Zoom -->
                        <div v-if="livePreview" class="flex flex-wrap items-start gap-8 pt-5 border-t border-gray-100">

                            <!-- Square preview (live) -->
                            <div class="flex flex-col gap-2 flex-shrink-0">
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Prévia do card</span>
                                <div class="w-44 h-44 rounded-xl overflow-hidden border-2 border-gray-200 shadow-sm">
                                    <img :src="livePreview" alt="square preview"
                                        class="w-full h-full object-cover transition-all duration-200"
                                        :style="{ objectPosition: coverPosition, transform: `scale(${coverZoom})`, transformOrigin: coverPosition }" />
                                </div>

                                <!-- Cover action: shown below the square preview -->
                                <div class="mt-1">
                                    <span v-if="deleteIndex === 0"
                                        class="inline-flex items-center gap-1.5 text-xs font-semibold text-primary">
                                        <i class="fa-solid fa-star text-xs"></i>
                                        Imagem de capa atual
                                    </span>
                                    <button v-else type="button"
                                        @click.prevent="setAsCover(deleteIndex)"
                                        class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-lg border border-yellow-400 text-yellow-600 bg-yellow-50 hover:bg-yellow-100 transition">
                                        <i class="fa-solid fa-star text-yellow-400"></i>
                                        Definir como Capa
                                    </button>
                                </div>
                            </div>

                            <!-- Position grid -->
                            <div class="flex flex-col gap-2">
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Ponto focal</span>
                                <p class="text-xs text-gray-400 -mt-1">Área visível ao recortar em quadrado</p>
                                <div class="inline-grid grid-cols-3 gap-1.5 mt-1">
                                    <!-- Row 1 -->
                                    <button type="button" @click.prevent="jumpPosition(0,0)" title="Superior esquerdo" :class="jumpBtnClass(0,0)">
                                        <i class="fa-solid fa-arrow-up-left"></i>
                                    </button>
                                    <button type="button" @click.prevent="stepPosition('y',-10)" title="Subir 10%" :class="stepBtnClass('y',-1)">
                                        <i class="fa-solid fa-arrow-up"></i>
                                    </button>
                                    <button type="button" @click.prevent="jumpPosition(100,0)" title="Superior direito" :class="jumpBtnClass(100,0)">
                                        <i class="fa-solid fa-arrow-up-right"></i>
                                    </button>
                                    <!-- Row 2 -->
                                    <button type="button" @click.prevent="stepPosition('x',-10)" title="Mover esquerda 10%" :class="stepBtnClass('x',-1)">
                                        <i class="fa-solid fa-arrow-left"></i>
                                    </button>
                                    <button type="button" @click.prevent="jumpPosition(50,50)" title="Centro" :class="jumpBtnClass(50,50)">
                                        <i class="fa-solid fa-circle-dot"></i>
                                    </button>
                                    <button type="button" @click.prevent="stepPosition('x',10)" title="Mover direita 10%" :class="stepBtnClass('x',1)">
                                        <i class="fa-solid fa-arrow-right"></i>
                                    </button>
                                    <!-- Row 3 -->
                                    <button type="button" @click.prevent="jumpPosition(0,100)" title="Inferior esquerdo" :class="jumpBtnClass(0,100)">
                                        <i class="fa-solid fa-arrow-down-left"></i>
                                    </button>
                                    <button type="button" @click.prevent="stepPosition('y',10)" title="Descer 10%" :class="stepBtnClass('y',1)">
                                        <i class="fa-solid fa-arrow-down"></i>
                                    </button>
                                    <button type="button" @click.prevent="jumpPosition(100,100)" title="Inferior direito" :class="jumpBtnClass(100,100)">
                                        <i class="fa-solid fa-arrow-down-right"></i>
                                    </button>
                                </div>
                                <span class="text-xs text-gray-400 mt-1">
                                    Foco: <strong class="text-gray-600">{{ positionLabel }}</strong>
                                </span>
                            </div>

                            <!-- Zoom slider -->
                            <div class="flex flex-col gap-2 min-w-[160px]">
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Zoom</span>
                                <p class="text-xs text-gray-400 -mt-1">Aproximação da imagem no card</p>
                                <div class="flex items-center gap-3 mt-2">
                                    <i class="fa-solid fa-magnifying-glass text-gray-400 text-sm"></i>
                                    <input type="range" min="1.0" max="3.0" step="0.1"
                                        v-model.number="coverZoom"
                                        @change="saveCoverZoom"
                                        class="w-full h-1.5 accent-primary cursor-pointer rounded-full" />
                                    <span class="text-xs font-semibold text-gray-600 w-10 text-right">{{ Math.round(coverZoom * 100) }}%</span>
                                </div>
                                <button v-if="coverZoom !== 1.0" type="button"
                                    @click="coverZoom = 1.0; saveCoverZoom()"
                                    class="text-xs text-primary hover:underline self-start mt-0.5">
                                    Resetar zoom
                                </button>
                            </div>

                        </div>

                    </div>
                </div>
            </div>

            <div class="db-card tab-content" id="variations">
                <ProductVariationListComponent />
            </div>

            <div class="db-card tab-content" id="barcode">
                <div class="db-card-header">
                    <h3 class="db-card-title">{{ $t('label.barcode') }}</h3>
                </div>
                <div class="db-card-body">
                    <div class="row px-3 py-0">
                        <div class="col-12 md:col-4" id="productBarcodePrint">
                            <img class="db-image" alt="product-barcode" :src="barcodeImage" />
                            <span class="mt-2">{{ product.sku }}</span>
                        </div>
                        <div class="col-12 md:col-8 hidden-print" v-if="barcodeImage">
                            <div class="flex items-center gap-6">
                                <button @click="downloadBarcode(product.sku)" type="button"
                                    class="flex items-center justify-center gap-1.5 h-10 px-6 db-btn text-white bg-primary">
                                    <i class="lab lab-fill-download"></i>
                                    <span class="capitalize text-sm font-medium">{{ $t('label.download') }}</span>
                                </button>
                                <PrintButtonComponent :props="printObj"
                                    :buttonClass="'flex items-center justify-center gap-1.5 h-10 px-6 db-btn text-white bg-success'" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="db-card tab-content" id="offer">
                <div class="db-card-header">
                    <h3 class="db-card-title">{{ $t('label.offer') }}</h3>
                </div>
                <div class="db-card-body">
                    <form @submit.prevent="offerSsave" class="d-block w-full">
                        <div class="form-row">
                            <div class="form-col-12 sm:form-col-6">
                                <label for="offer_start_date" class="db-field-title required">{{
                                    $t("label.offer_start_date")
                                    }}</label>
                                <Datepicker hideInputIcon autoApply v-model="form.offer_start_date"
                                    :enableTimePicker="true" :is24="false" :monthChangeOnScroll="false" utc="false"
                                    :input-class-name="offerError.offer_start_date ? 'invalid' : ''">
                                    <template #am-pm-button="{ toggle, value }">
                                        <button @click="toggle">{{ value }}</button>
                                    </template>
                                </Datepicker>
                                <small class="db-field-alert" v-if="offerError.offer_start_date">{{
                                    offerError.offer_start_date[0]
                                    }}</small>
                            </div>
                            <div class="form-col-12 sm:form-col-6">
                                <label for="offer_end_date" class="db-field-title required">{{
                                    $t("label.offer_end_date")
                                    }}</label>
                                <Datepicker hideInputIcon autoApply v-model="form.offer_end_date"
                                    :enableTimePicker="true" :is24="false" :monthChangeOnScroll="false" utc="false"
                                    :input-class-name="offerError.offer_end_date ? 'invalid' : ''">
                                    <template #am-pm-button="{ toggle, value }">
                                        <button @click="toggle">{{ value }}</button>
                                    </template>
                                </Datepicker>
                                <small class="db-field-alert" v-if="offerError.offer_end_date">{{
                                    offerError.offer_end_date[0]
                                    }}</small>
                            </div>

                            <div class="form-col-12 sm:form-col-6">
                                <label for="discount" class="db-field-title required">
                                    {{ $t("label.discount_percentage") }}
                                </label>
                                <input v-model="form.discount" v-on:keypress="floatNumber($event)"
                                    v-bind:class="offerError.discount ? 'invalid' : ''" type="text" id="discount"
                                    class="db-field-control" />
                                <small class="db-field-alert" v-if="offerError.discount">{{ offerError.discount[0]
                                    }}</small>
                            </div>

                            <div class="form-col-12 sm:form-col-6">
                                <label class="db-field-title required" for="yes">{{ $t("label.add_to_flash_sale")
                                    }}</label>
                                <div class="db-field-radio-group">
                                    <div class="db-field-radio">
                                        <div class="custom-radio">
                                            <input type="radio" v-model="form.add_to_flash_sale" id="yes"
                                                :value="enums.askEnum.YES" class="custom-radio-field">
                                            <span class="custom-radio-span"></span>
                                        </div>
                                        <label for="yes" class="db-field-label">{{ $t('label.yes') }}</label>
                                    </div>
                                    <div class="db-field-radio">
                                        <div class="custom-radio">
                                            <input type="radio" class="custom-radio-field"
                                                v-model="form.add_to_flash_sale" id="no" :value="enums.askEnum.NO">
                                            <span class="custom-radio-span"></span>
                                        </div>
                                        <label for="no" class="db-field-label">{{ $t('label.no') }}</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-col-12 flex items-center gap-4">
                                <button type="submit" class="db-btn text-white bg-primary">
                                    <i class="lab lab-fill-save"></i>
                                    <span>{{ $t("button.save") }}</span>
                                </button>

                                <button type="button" class="db-btn border" @click="clearOffer()">
                                    <i class="fa-solid fa-circle-xmark"></i>
                                    <span>{{ $t("button.remove") }}</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="db-card tab-content" id="video">
                <ProductVideoListComponent />
            </div>

            <div class="db-card tab-content" id="shippingReturn">
                <div class="db-card-header">
                    <h3 class="db-card-title">{{ $t('label.shipping_and_return') }}</h3>
                </div>
                <div class="db-card-body">
                    <form @submit.prevent="saveShippingAndReturn" class="d-block w-full">
                        <div class="form-row py-2">
                            <div class="form-col-12 sm:form-col-6">
                                <label class="db-field-title required">{{ $t("label.shipping_type") }}</label>
                                <div class="db-field-radio-group">
                                    <div class="db-field-radio">
                                        <div class="custom-radio">
                                            <input type="radio" v-model="shippingAndReturnForm.shipping_type" id="free"
                                                :value="enums.shippingTypeEnum.FREE" class="custom-radio-field">
                                            <span class="custom-radio-span"></span>
                                        </div>
                                        <label for="free" class="db-field-label">{{ $t('label.free') }}</label>
                                    </div>
                                    <div class="db-field-radio">
                                        <div class="custom-radio">
                                            <input type="radio" class="custom-radio-field"
                                                v-model="shippingAndReturnForm.shipping_type" id="flat_rate"
                                                :value="enums.shippingTypeEnum.FLAT_RATE">
                                            <span class="custom-radio-span"></span>
                                        </div>
                                        <label for="flat_rate" class="db-field-label">{{ $t('label.flat_rate')
                                            }}</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-col-12 sm:form-col-6"
                                v-if="shippingAndReturnForm.shipping_type === enums.shippingTypeEnum.FLAT_RATE">
                                <label for="shipping_cost" class="db-field-title required">{{ $t("label.shipping_cost")
                                    }}</label>
                                <input v-on:keypress="floatNumber($event)" v-model="shippingAndReturnForm.shipping_cost"
                                    v-bind:class="shippingError.shipping_cost ? 'invalid' : ''" type="text"
                                    id="shipping_cost" class="db-field-control">
                                <small class="db-field-alert" v-if="shippingError.shipping_cost">{{
                                    shippingError.shipping_cost[0]
                                    }}</small>
                            </div>

                            <div class="form-col-12 sm:form-col-6 mb-2"
                                v-if="shippingAndReturnForm.shipping_type === enums.shippingTypeEnum.FLAT_RATE">
                                <label class="db-field-title required">
                                    {{ $t("label.is_product_quantity_multiply") }}
                                </label>
                                <div class="db-field-radio-group">
                                    <div class="db-field-radio">
                                        <div class="custom-radio">
                                            <input type="radio"
                                                v-model="shippingAndReturnForm.is_product_quantity_multiply"
                                                id="quantityMultiplyYes" :value="enums.askEnum.YES"
                                                class="custom-radio-field">
                                            <span class="custom-radio-span"></span>
                                        </div>
                                        <label for="quantityMultiplyYes" class="db-field-label">{{ $t('label.yes')
                                            }}</label>
                                    </div>
                                    <div class="db-field-radio">
                                        <div class="custom-radio">
                                            <input type="radio" class="custom-radio-field"
                                                v-model="shippingAndReturnForm.is_product_quantity_multiply"
                                                id="quantityMultiplyNo" :value="enums.askEnum.NO">
                                            <span class="custom-radio-span"></span>
                                        </div>
                                        <label for="quantityMultiplyNo" class="db-field-label">{{ $t('label.no')
                                            }}</label>
                                    </div>
                                </div>
                            </div>


                            <div class="form-col-12">
                                <label class="db-field-title">{{ $t("label.shipping_and_return")
                                    }}</label>
                                <div
                                    :class="shippingError.shipping_and_return ? 'invalid textarea-error-box-style' : ''">
                                    <quill-editor id="description"
                                        v-model:value="shippingAndReturnForm.shipping_and_return"
                                        class="!h-80 textarea-border-radius" />
                                </div>
                                <small class="db-field-alert" v-if="shippingError.shipping_and_return">
                                    {{ shippingError.shipping_and_return[0] }}
                                </small>
                            </div>
                            <div class="form-col-12">
                                <button type="submit" class="db-btn py-2 text-white bg-primary">
                                    <i class="lab lab-fill-save"></i>
                                    <span class="hidden sm:inline-block">{{ $t("button.save") }}</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="db-card tab-content" id="seo">
                <ProductSeoComponent />
            </div>
        </div>
    </div>
</template>

<script>
import LoadingComponent from "../components/LoadingComponent";
import statusEnum from "../../../enums/modules/statusEnum";
import shippingTypeEnum from "../../../enums/modules/shippingTypeEnum";
import askEnum from "../../../enums/modules/askEnum";
import appService from "../../../services/appService";
import targetService from "../../../services/targetService";
import alertService from "../../../services/alertService";
import ProductVariationListComponent from "./variation/ProductVariationListComponent";
import ProductSeoComponent from "./seo/ProductSeoComponent";
import ProductVideoListComponent from "./video/ProductVideoListComponent";
import { quillEditor } from 'vue3-quill'
import Datepicker from "@vuepic/vue-datepicker";
import activityEnum from "../../../enums/modules/activityEnum";
import PrintButtonComponent from "../components/buttons/PrintButtonComponent.vue";

export default {
    name: "ProductShowComponent",
    components: {
        LoadingComponent,
        ProductVariationListComponent,
        ProductVideoListComponent,
        quillEditor,
        ProductSeoComponent,
        Datepicker,
        PrintButtonComponent
    },
    data() {
        return {
            loading: {
                isActive: false,
            },
            printObj: {
                id: "productBarcodePrint",
                popTitle: this.$t('menu.products')
            },
            tabMore: false,
            enums: {
                statusEnum: statusEnum,
                askEnum: askEnum,
                shippingTypeEnum: shippingTypeEnum,
                statusEnumArray: {
                    [statusEnum.ACTIVE]: this.$t("label.active"),
                    [statusEnum.INACTIVE]: this.$t("label.inactive"),
                },
                shippingTypeEnumArray: {
                    [shippingTypeEnum.FREE]: this.$t("label.free"),
                    [shippingTypeEnum.FLAT_RATE]: this.$t("label.flat_rate"),
                },
                askEnumArray: {
                    [askEnum.YES]: this.$t("label.yes"),
                    [askEnum.NO]: this.$t("label.no"),
                },
                activityEnumArray: {
                    [activityEnum.ENABLE]: this.$t("label.enable"),
                    [activityEnum.DISABLE]: this.$t("label.disable"),
                }
            },
            deleteIndex: 0,
            imageCount: 0,
            defaultImage: null,
            previewImage: null,
            barcodeImage: null,
            livePreview: null,
            coverX: 50,
            coverY: 50,
            coverZoom: 1.0,
            form: {
                add_to_flash_sale: "",
                discount: "",
                offer_start_date: "",
                offer_end_date: "",
            },
            shippingAndReturnForm: {
                shipping_type: shippingTypeEnum.FREE,
                shipping_cost: 0,
                is_product_quantity_multiply: askEnum.NO,
                shipping_and_return: "",
            },
            shippingError: {},
            offerError: {},
        };
    },
    computed: {
        product: function () {
            return this.$store.getters["product/show"];
        },
        coverPosition() {
            return `${this.coverX}% ${this.coverY}%`;
        },
        positionLabel() {
            const named = [
                {x:0,  y:0,   label:'Superior esquerdo'}, {x:50, y:0,   label:'Superior central'}, {x:100,y:0,   label:'Superior direito'},
                {x:0,  y:50,  label:'Meio esquerdo'},     {x:50, y:50,  label:'Centro'},            {x:100,y:50,  label:'Meio direito'},
                {x:0,  y:100, label:'Inferior esquerdo'}, {x:50, y:100, label:'Inferior central'},  {x:100,y:100, label:'Inferior direito'},
            ];
            const found = named.find(p => p.x === this.coverX && p.y === this.coverY);
            return found ? found.label : `X: ${this.coverX}%  Y: ${this.coverY}%`;
        },
    },
    mounted() {
        this.loading.isActive = true;
        this.show();
    },
    methods: {
        multiTargets: function (event, commonBtnClass, commonDivClass, targetID) {
            targetService.multiTargets(event, commonBtnClass, commonDivClass, targetID);
        },
        floatNumber(e) {
            return appService.floatNumber(e);
        },
        switchImage: function (link, index) {
            this.livePreview = link;
            this.deleteIndex = index;
        },
        show: function () {
            this.$store.dispatch("product/show", this.$route.params.id).then((res) => {
                this.defaultImage = res.data.data.preview;
                this.previewImage = res.data.data.preview;
                this.barcodeImage = res.data.data.barcode_image;
                this.livePreview = res.data.data.image;
                this.imageCount = res.data.data.images.length;
                const pos = this.parsePositionString(res.data.data.cover_position);
                this.coverX = pos.x;
                this.coverY = pos.y;
                this.coverZoom = res.data.data.cover_zoom || 1.0;
                this.shippingAndReturnForm.shipping_and_return = res.data.data.shipping_and_return;
                this.shippingAndReturnForm.shipping_type = res.data.data.shipping_type;
                this.shippingAndReturnForm.shipping_cost = res.data.data.shipping_cost;
                this.shippingAndReturnForm.is_product_quantity_multiply = res.data.data.is_product_quantity_multiply;
                this.form.add_to_flash_sale = res.data.data.add_to_flash_sale;
                this.form.discount = res.data.data.discount_percentage;
                this.form.offer_start_date = res.data.data.offer_start_date;
                this.form.offer_end_date = res.data.data.offer_end_date;
                this.loading.isActive = false;
            }).catch((error) => {
                this.loading.isActive = false;
            });
        },
        saveImage: function () {
            if (this.$refs.imageProperty.files[0]) {
                try {
                    this.loading.isActive = true;
                    const formData = new FormData();
                    formData.append("image", this.$refs.imageProperty.files[0]);
                    this.$store.dispatch("product/uploadImage", {
                        id: this.$route.params.id,
                        form: formData
                    }).then((res) => {
                        alertService.success(this.$t("message.image_update"));
                        this.defaultImage = res.data.data.preview;
                        this.previewImage = res.data.data.preview;
                        this.livePreview = res.data.data.image;
                        this.imageCount = res.data.data.images.length;

                        if (this.$refs.imageProperty) {
                            this.$refs.imageProperty.value = null;
                        }
                        this.loading.isActive = false;
                    }).catch((err) => {
                        this.loading.isActive = false;
                        alertService.error(err.response.data.errors.image[0]);
                    });
                } catch (err) {
                    this.loading.isActive = false;
                    alertService.error(err.response.data.message);
                }
            }
        },
        deleteImage: function () {
            appService.destroyConfirmation().then((res) => {
                try {
                    this.loading.isActive = true;
                    this.$store.dispatch("product/deleteImage", {
                        id: this.$route.params.id,
                        index: this.deleteIndex,
                    }).then((res) => {
                        this.show();
                        this.loading.isActive = false;
                        alertService.success(this.$t("message.image_delete"));
                        this.deleteIndex = 0;
                    }).catch((err) => {
                        this.loading.isActive = false;
                        alertService.error(err.response.data.message);
                    });
                } catch (err) {
                    this.loading.isActive = false;
                    alertService.error(err.response.data.message);
                }
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
        parsePositionString(str) {
            if (!str) return { x: 50, y: 50 };
            const pct = str.match(/^(\d+(?:\.\d+)?)%\s+(\d+(?:\.\d+)?)%$/);
            if (pct) return { x: Math.round(parseFloat(pct[1])), y: Math.round(parseFloat(pct[2])) };
            const map = {
                'left top': {x:0,y:0},    'center top': {x:50,y:0},    'right top': {x:100,y:0},
                'left center': {x:0,y:50}, 'center': {x:50,y:50},      'right center': {x:100,y:50},
                'left bottom': {x:0,y:100},'center bottom': {x:50,y:100},'right bottom': {x:100,y:100},
            };
            return map[str] || { x: 50, y: 50 };
        },
        jumpBtnClass(x, y) {
            const active = this.coverX === x && this.coverY === y;
            return [
                'w-10 h-10 rounded-lg border text-sm transition-all duration-150 flex items-center justify-center',
                active ? 'bg-primary border-primary text-white shadow-md' : 'bg-white border-gray-200 text-gray-500 hover:border-primary hover:text-primary',
            ];
        },
        stepBtnClass(axis, dir) {
            const atLimit = (axis === 'x' && dir < 0 && this.coverX === 0)
                || (axis === 'x' && dir > 0 && this.coverX === 100)
                || (axis === 'y' && dir < 0 && this.coverY === 0)
                || (axis === 'y' && dir > 0 && this.coverY === 100);
            return [
                'w-10 h-10 rounded-lg border text-sm transition-all duration-150 flex items-center justify-center',
                atLimit ? 'bg-gray-50 border-gray-100 text-gray-300 cursor-not-allowed' : 'bg-white border-gray-200 text-gray-500 hover:border-primary hover:text-primary',
            ];
        },
        jumpPosition(x, y) {
            this.coverX = x;
            this.coverY = y;
            this.saveCoverPositionNow();
        },
        stepPosition(axis, delta) {
            if (axis === 'x') {
                this.coverX = Math.min(100, Math.max(0, this.coverX + delta));
            } else {
                this.coverY = Math.min(100, Math.max(0, this.coverY + delta));
            }
            this.saveCoverPositionNow();
        },
        saveCoverPositionNow() {
            this.$store.dispatch('product/updateCoverPosition', {
                id: this.$route.params.id,
                position: this.coverPosition,
            }).catch((err) => {
                alertService.error(err?.response?.data?.message || 'Erro ao salvar posição');
            });
        },
        saveCoverZoom: function () {
            this.$store.dispatch('product/updateCoverZoom', {
                id: this.$route.params.id,
                zoom: this.coverZoom,
            }).catch((err) => {
                alertService.error(err?.response?.data?.message || 'Erro ao salvar zoom');
            });
        },
        setAsCover: function (index) {
            this.loading.isActive = true;
            this.$store.dispatch('product/setCoverImage', {
                id: this.$route.params.id,
                index,
            }).then((res) => {
                this.loading.isActive = false;
                this.livePreview  = res.data.data.image;
                this.imageCount   = res.data.data.images.length;
                alertService.success(this.$t('message.update'));
            }).catch((err) => {
                this.loading.isActive = false;
                alertService.error(err?.response?.data?.message || 'Erro ao definir capa');
            });
        },
        saveShippingAndReturn: function () {
            try {
                this.loading.isActive = true;
                this.$store.dispatch("product/shippingAndReturn", {
                    id: this.$route.params.id,
                    form: this.shippingAndReturnForm,
                }).then((res) => {
                    alertService.success(this.$t("message.shipping_and_return"));
                    this.loading.isActive = false;
                    this.shippingError = {};
                }).catch((err) => {
                    this.loading.isActive = false;
                    this.shippingError = err.response.data.errors;
                });
            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err.response.data.message);
            }
        },
        offerSsave: function () {
            try {
                this.loading.isActive = true;
                this.$store.dispatch("product/productOffer", {
                    id: this.$route.params.id,
                    form: this.form,
                }).then((res) => {
                    alertService.success(this.$t("message.product_offer"));
                    this.loading.isActive = false;
                    this.offerError = {};
                }).catch((err) => {
                    this.loading.isActive = false;
                    this.offerError = err.response.data.errors;
                });
            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err.response.data.message);
            }
        },
        downloadBarcode: function (sku) {
            if (!isNaN(this.$route.params.id)) {
                this.loading.isActive = true;
                this.$store.dispatch("product/downloadBarcode", this.$route.params.id).then((res) => {
                    this.loading.isActive = false;

                    let fileType = "";
                    if (res.data.type) {
                        let type = res.data.type;
                        type = type.split("/");
                        fileType = type[1];
                    }

                    if (res.data.size > 0) {
                        const url = window.URL.createObjectURL(
                            new Blob([res.data])
                        );
                        const link = document.createElement("a");
                        link.href = url;
                        link.download =
                            "" + sku + "." + fileType;
                        link.click();
                        URL.revokeObjectURL(link.href);
                    } else {
                        alertService.info(this.$t("menu.products") + " " + this.$t('message.barcode_not_found'));
                    }

                }).catch((err) => {
                    this.loading.isActive = false;
                });
            }
        },
        clearOffer: function(){
            appService.submitConfirmation().then((res) => {
                try {
                    this.loading.isActive = true;
                    this.$store.dispatch('product/clearOffer', this.$route.params.id).then((res) => {
                        this.loading.isActive = false;
                        alertService.success(this.$t('message.offer_cleared_success'));

                        this.form =  {
                            add_to_flash_sale: askEnum.NO,
                            discount: "",
                            offer_start_date: "",
                            offer_end_date: "",
                        }
                    }).catch((err) => {
                        this.loading.isActive = false;
                        alertService.error(err.response.data.message);
                    })
                } catch (err) {
                    this.loading.isActive = false;
                    alertService.error(err.response.data.message);
                }
            }).catch((err) => {
                this.loading.isActive = false;
            })
        }
    },
};
</script>