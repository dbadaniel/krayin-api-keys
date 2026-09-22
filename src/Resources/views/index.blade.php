<x-admin::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('api_key::app.admin.api-keys.index.title')
    </x-slot>

    <v-api-keys>
        <!-- DataGrid Shimmer -->
        <x-admin::shimmer.datagrid />
    </v-api-keys>

    @pushOnce('scripts')
        <script type="text/x-template" id="api-keys-template">
            <div class="flex flex-col gap-4">
                <!-- Header Section -->
                <div class="scroll-reactive-sticky sticky top-[60px] z-[1000] flex items-center justify-between rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm shadow-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                    <div class="flex flex-col gap-2">
                        <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                            <span>@lang('admin::app.layouts.settings')</span>
                            <span>/</span>
                            <span>@lang('api_key::app.admin.api-keys.index.title')</span>
                        </div>

                        <div class="text-xl font-bold dark:text-white">
                            @lang('api_key::app.admin.api-keys.index.title')
                        </div>
                    </div>

                    <div class="flex items-center gap-x-2.5">
                        <button
                            type="button"
                            class="primary-button"
                            @click="openCreateModal"
                        >
                            @lang('api_key::app.admin.api-keys.index.create-btn')
                        </button>
                    </div>
                </div>

                <!-- Info description banner -->
                <div class="rounded-lg border border-blue-200 bg-blue-50 p-4 text-sm text-blue-800 dark:border-blue-900/50 dark:bg-blue-950/30 dark:text-blue-300">
                    <p>@lang('api_key::app.admin.api-keys.index.description')</p>
                </div>

                <!-- Datagrid -->
                <x-admin::datagrid
                    :src="route('admin.settings.api_keys.index')"
                    ref="datagrid"
                >
                    <x-admin::shimmer.datagrid />
                </x-admin::datagrid>

                <!-- Modal 1: Create API Key Form -->
                <x-admin::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                    ref="modalForm"
                >
                    <form @submit="handleSubmit($event, storeKey)">
                        <x-admin::modal ref="createModal">
                            <!-- Modal Header -->
                            <x-slot:header>
                                <p class="text-lg font-bold text-gray-800 dark:text-white">
                                    @lang('api_key::app.admin.api-keys.create.title')
                                </p>
                            </x-slot>

                            <!-- Modal Content -->
                            <x-slot:content>
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label class="required">
                                        @lang('api_key::app.admin.api-keys.create.name')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        id="name"
                                        name="name"
                                        rules="required|max:100"
                                        :label="trans('api_key::app.admin.api-keys.create.name')"
                                        :placeholder="trans('api_key::app.admin.api-keys.create.name-placeholder')"
                                        v-model="keyName"
                                    />

                                    <x-admin::form.control-group.error control-name="name" />
                                </x-admin::form.control-group>
                            </x-slot>

                            <!-- Modal Footer -->
                            <x-slot:footer>
                                <button
                                    type="button"
                                    class="secondary-button"
                                    @click="$refs.createModal.toggle()"
                                >
                                    @lang('api_key::app.admin.api-keys.create.cancel-btn')
                                </button>

                                <x-admin::button
                                    button-type="submit"
                                    class="primary-button"
                                    :title="trans('api_key::app.admin.api-keys.create.save-btn')"
                                    ::loading="isProcessing"
                                    ::disabled="isProcessing"
                                />
                            </x-slot>
                        </x-admin::modal>
                    </form>
                </x-admin::form>

                <!-- Modal 2: Token Display with Copy Button -->
                <x-admin::modal ref="tokenDisplayModal">
                    <x-slot:header>
                        <p class="text-lg font-bold text-emerald-600 dark:text-emerald-400 flex items-center gap-2">
                            <span>🔑</span>
                            <span>@lang('api_key::app.admin.api-keys.created.title')</span>
                        </p>
                    </x-slot>

                    <x-slot:content>
                        <div class="flex flex-col gap-4 py-2">
                            <!-- Security Warning Alert -->
                            <div class="rounded-lg border border-amber-300 bg-amber-50 p-3 text-xs text-amber-900 dark:border-amber-900/50 dark:bg-amber-950/40 dark:text-amber-300">
                                <strong>⚠️ @lang('api_key::app.admin.api-keys.created.warning')</strong>
                            </div>

                            <!-- Token Copy Box -->
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-semibold text-gray-700 dark:text-gray-300">
                                    Token:
                                </label>
                                <div class="flex items-center gap-2">
                                    <input
                                        type="text"
                                        :value="plainTextToken"
                                        readonly
                                        class="w-full rounded-md border border-gray-300 bg-gray-50 px-3 py-2 font-mono text-sm text-gray-900 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 select-all"
                                        @click="$event.target.select()"
                                    />

                                    <button
                                        type="button"
                                        class="primary-button whitespace-nowrap"
                                        @click="copyToClipboard"
                                    >
                                        <span v-if="copied">✓ @lang('api_key::app.admin.api-keys.created.copied')</span>
                                        <span v-else>📋 @lang('api_key::app.admin.api-keys.created.copy-btn')</span>
                                    </button>
                                </div>
                            </div>

                            <!-- How to use -->
                            <div class="mt-2 rounded-lg border border-gray-200 bg-gray-50 p-3 text-xs text-gray-700 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 flex flex-col gap-1.5">
                                <span class="font-semibold">@lang('api_key::app.admin.api-keys.created.how-to-use')</span>
                                <p class="text-gray-500 dark:text-gray-400">@lang('api_key::app.admin.api-keys.created.how-to-use-desc')</p>
                                <pre class="overflow-x-auto rounded bg-gray-900 p-2 text-[11px] font-mono text-emerald-400 dark:bg-black">Authorization: Bearer @{{ plainTextToken }}</pre>
                            </div>
                        </div>
                    </x-slot>

                    <x-slot:footer>
                        <button
                            type="button"
                            class="primary-button w-full justify-center"
                            @click="closeTokenModal"
                        >
                            @lang('api_key::app.admin.api-keys.created.close-btn')
                        </button>
                    </x-slot>
                </x-admin::modal>
            </div>
        </script>

        <script type="module">
            app.component('v-api-keys', {
                template: '#api-keys-template',

                data() {
                    return {
                        isProcessing: false,
                        keyName: '',
                        plainTextToken: '',
                        copied: false,
                    };
                },

                methods: {
                    openCreateModal() {
                        this.keyName = '';
                        this.plainTextToken = '';
                        this.copied = false;
                        this.$refs.createModal.toggle();
                    },

                    storeKey(params, { resetForm, setErrors }) {
                        this.isProcessing = true;

                        this.$axios.post("{{ route('admin.settings.api_keys.store') }}", {
                            name: this.keyName || params.name,
                        })
                        .then(response => {
                            this.isProcessing = false;
                            this.plainTextToken = response.data.plain_text_token;

                            // Close creation modal
                            this.$refs.createModal.toggle();
                            resetForm();

                            // Open token display modal
                            this.$refs.tokenDisplayModal.toggle();

                            this.$emitter.emit('add-flash', {
                                type: 'success',
                                message: response.data.message
                            });

                            if (this.$refs.datagrid) {
                                this.$refs.datagrid.get();
                            }
                        })
                        .catch(error => {
                            this.isProcessing = false;

                            if (error.response && error.response.status === 422) {
                                setErrors(error.response.data.errors);
                            } else {
                                this.$emitter.emit('add-flash', {
                                    type: 'error',
                                    message: error.response?.data?.message || 'Erro ao gerar chave.'
                                });
                            }
                        });
                    },

                    copyToClipboard() {
                        if (navigator.clipboard && window.isSecureContext) {
                            navigator.clipboard.writeText(this.plainTextToken);
                        } else {
                            const input = document.createElement('input');
                            input.value = this.plainTextToken;
                            document.body.appendChild(input);
                            input.select();
                            document.execCommand('copy');
                            document.body.removeChild(input);
                        }

                        this.copied = true;
                        setTimeout(() => {
                            this.copied = false;
                        }, 3000);
                    },

                    closeTokenModal() {
                        this.$refs.tokenDisplayModal.toggle();
                        this.plainTextToken = '';
                        this.copied = false;
                        if (this.$refs.datagrid) {
                            this.$refs.datagrid.get();
                        }
                    },
                },
            });
        </script>
    @endPushOnce
</x-admin::layouts>
