<x-admin::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('api_key::app.admin.api-keys.index.title')
    </x-slot>

    <v-api-keys
        :users='@json($users)'
        :current-user-id="{{ $currentUser->id }}"
        :can-manage-all="{{ $canManageAll ? 'true' : 'false' }}"
    >
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
                <div class="rounded-lg border border-blue-200 bg-blue-50 p-4 text-sm text-blue-800 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
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
                        <x-admin::modal ref="createModal" size="medium">
                            <!-- Modal Header -->
                            <x-slot:header>
                                <p class="text-lg font-bold text-gray-800 dark:text-white">
                                    @lang('api_key::app.admin.api-keys.create.title')
                                </p>
                            </x-slot>

                            <!-- Modal Content -->
                            <x-slot:content>
                                <div class="flex flex-col gap-4 py-2">
                                    <!-- 1. Key Name -->
                                    <x-admin::form.control-group>
                                        <x-admin::form.control-group.label class="required text-gray-700 dark:text-gray-200">
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

                                    <!-- 2. Associated User / Login -->
                                    <div v-if="canManageAll && users.length > 1" class="flex flex-col gap-1.5">
                                        <label for="user_id" class="text-xs font-semibold text-gray-700 dark:text-gray-200">
                                            @lang('api_key::app.admin.api-keys.create.user')
                                        </label>

                                        <select
                                            id="user_id"
                                            name="user_id"
                                            v-model="selectedUserId"
                                            class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800 transition focus:border-brandColor focus:outline-none dark:border-gray-800 dark:bg-gray-900 dark:text-gray-200"
                                        >
                                            <option v-for="user in users" :key="user.id" :value="user.id">
                                                @{{ user.name }} (@{{ user.email }})
                                            </option>
                                        </select>

                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            @lang('api_key::app.admin.api-keys.create.user-help')
                                        </span>
                                    </div>

                                    <!-- 3. Permissions Section -->
                                    <div class="flex flex-col gap-2.5 pt-2">
                                        <label class="text-xs font-semibold text-gray-700 dark:text-gray-200">
                                            @lang('api_key::app.admin.api-keys.create.permissions')
                                        </label>

                                        <!-- Radio: All vs Custom -->
                                        <div class="flex flex-wrap items-center gap-6">
                                            <label class="inline-flex cursor-pointer items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                                <input
                                                    type="radio"
                                                    value="all"
                                                    v-model="permissionType"
                                                    class="text-blue-600 focus:ring-blue-500"
                                                />
                                                <span class="font-medium">@lang('api_key::app.admin.api-keys.create.all-permissions')</span>
                                            </label>

                                            <label class="inline-flex cursor-pointer items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                                <input
                                                    type="radio"
                                                    value="custom"
                                                    v-model="permissionType"
                                                    class="text-blue-600 focus:ring-blue-500"
                                                />
                                                <span class="font-medium">@lang('api_key::app.admin.api-keys.create.custom-permissions')</span>
                                            </label>
                                        </div>

                                        <!-- Custom Permissions Grid -->
                                        <div v-show="permissionType === 'custom'" class="mt-2 flex flex-col gap-3 rounded-lg border border-gray-200 bg-gray-50 p-3.5 dark:border-gray-800 dark:bg-gray-950">
                                            <!-- Quick selection actions -->
                                            <div class="flex items-center justify-between border-b border-gray-200 pb-2 dark:border-gray-800">
                                                <span class="text-xs font-semibold text-gray-600 dark:text-gray-400">
                                                    Escopos Selecionados: @{{ selectedAbilities.length }}
                                                </span>

                                                <div class="flex items-center gap-2 text-xs">
                                                    <button
                                                        type="button"
                                                        class="cursor-pointer font-medium text-blue-600 hover:underline dark:text-blue-400"
                                                        @click="selectAllAbilities"
                                                    >
                                                        @lang('api_key::app.admin.api-keys.create.select-all')
                                                    </button>
                                                    <span class="text-gray-400">|</span>
                                                    <button
                                                        type="button"
                                                        class="cursor-pointer font-medium text-red-600 hover:underline dark:text-red-400"
                                                        @click="deselectAllAbilities"
                                                    >
                                                        @lang('api_key::app.admin.api-keys.create.deselect-all')
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Modules List -->
                                            <div class="grid grid-cols-1 gap-2.5 sm:grid-cols-2">
                                                <div
                                                    v-for="mod in availableModules"
                                                    :key="mod.key"
                                                    class="flex flex-col gap-1.5 rounded-md border border-gray-200 bg-white p-2.5 shadow-xs dark:border-gray-800 dark:bg-gray-900"
                                                >
                                                    <div class="flex items-center justify-between">
                                                        <span class="text-xs font-bold text-gray-800 dark:text-white">
                                                            @{{ mod.name }}
                                                        </span>

                                                        <button
                                                            type="button"
                                                            class="text-[11px] text-gray-500 hover:text-blue-600 dark:hover:text-blue-400"
                                                            @click="toggleModule(mod.key)"
                                                        >
                                                            @{{ isModuleFull(mod.key) ? 'Desmarcar' : 'Ambos' }}
                                                        </button>
                                                    </div>

                                                    <div class="flex items-center gap-3 pt-1 text-xs text-gray-600 dark:text-gray-300">
                                                        <label class="inline-flex cursor-pointer items-center gap-1.5">
                                                            <input
                                                                type="checkbox"
                                                                :value="mod.key + ':read'"
                                                                v-model="selectedAbilities"
                                                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                                            />
                                                            <span>@lang('api_key::app.admin.api-keys.create.read')</span>
                                                        </label>

                                                        <label class="inline-flex cursor-pointer items-center gap-1.5">
                                                            <input
                                                                type="checkbox"
                                                                :value="mod.key + ':write'"
                                                                v-model="selectedAbilities"
                                                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                                            />
                                                            <span>@lang('api_key::app.admin.api-keys.create.write')</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
                        <p class="flex items-center gap-2 text-lg font-bold text-gray-800 dark:text-white">
                            <span class="text-xl">🔑</span>
                            <span>@lang('api_key::app.admin.api-keys.created.title')</span>
                        </p>
                    </x-slot>

                    <x-slot:content>
                        <div class="flex flex-col gap-4 py-2">
                            <!-- Security Warning Alert -->
                            <div class="rounded-lg border p-3 text-xs" style="background-color: rgba(245, 158, 11, 0.12); border-color: rgba(245, 158, 11, 0.4); color: #fbbf24;">
                                <strong class="flex items-center gap-1.5 text-xs font-semibold" style="color: #fcd34d;">
                                    <span>⚠️</span>
                                    <span>@lang('api_key::app.admin.api-keys.created.warning')</span>
                                </strong>
                            </div>

                            <!-- Token Info Summary -->
                            <div v-if="createdKeyInfo" class="flex flex-wrap items-center gap-2 text-xs text-gray-600 dark:text-gray-300">
                                <span><strong>Aplicação:</strong> @{{ createdKeyInfo.name }}</span>
                                <span>•</span>
                                <span><strong>Usuário:</strong> @{{ createdKeyInfo.user_name }}</span>
                            </div>

                            <!-- Token Copy Box -->
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-semibold text-gray-700 dark:text-gray-200">
                                    Token:
                                </label>
                                <div class="flex items-center gap-2">
                                    <input
                                        type="text"
                                        :value="plainTextToken"
                                        readonly
                                        class="w-full rounded-md border border-gray-300 bg-gray-50 px-3 py-2 font-mono text-sm select-all dark:border-gray-700"
                                        style="background-color: #090d16; color: #34d399; border: 1px solid #374151;"
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
                            <div class="mt-2 flex flex-col gap-1.5 rounded-lg border border-gray-200 bg-gray-50 p-3 text-xs text-gray-700 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                                <span class="font-semibold text-gray-800 dark:text-white">@lang('api_key::app.admin.api-keys.created.how-to-use')</span>
                                <p class="text-gray-600 dark:text-gray-400">@lang('api_key::app.admin.api-keys.created.how-to-use-desc')</p>
                                <pre class="overflow-x-auto rounded p-2.5 text-[12px] font-mono" style="background-color: #030712; border: 1px solid #1f2937; color: #10b981;">Authorization: Bearer @{{ plainTextToken }}</pre>
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

                props: {
                    users: {
                        type: Array,
                        default: () => []
                    },
                    currentUserId: {
                        type: [Number, String],
                        default: null
                    },
                    canManageAll: {
                        type: Boolean,
                        default: false
                    }
                },

                data() {
                    return {
                        isProcessing: false,
                        keyName: '',
                        selectedUserId: this.currentUserId,
                        permissionType: 'all',
                        selectedAbilities: [],
                        plainTextToken: '',
                        createdKeyInfo: null,
                        copied: false,
                        availableModules: [
                            { key: 'leads', name: '@lang('api_key::app.admin.api-keys.create.modules.leads')' },
                            { key: 'contacts', name: '@lang('api_key::app.admin.api-keys.create.modules.contacts')' },
                            { key: 'quotes', name: '@lang('api_key::app.admin.api-keys.create.modules.quotes')' },
                            { key: 'products', name: '@lang('api_key::app.admin.api-keys.create.modules.products')' },
                            { key: 'activities', name: '@lang('api_key::app.admin.api-keys.create.modules.activities')' },
                            { key: 'mails', name: '@lang('api_key::app.admin.api-keys.create.modules.mails')' },
                            { key: 'settings', name: '@lang('api_key::app.admin.api-keys.create.modules.settings')' },
                            { key: 'configuration', name: '@lang('api_key::app.admin.api-keys.create.modules.configuration')' }
                        ]
                    };
                },

                methods: {
                    openCreateModal() {
                        this.keyName = '';
                        this.selectedUserId = this.currentUserId;
                        this.permissionType = 'all';
                        this.selectedAbilities = [];
                        this.plainTextToken = '';
                        this.createdKeyInfo = null;
                        this.copied = false;
                        this.$refs.createModal.toggle();
                    },

                    isModuleFull(moduleKey) {
                        return this.selectedAbilities.includes(moduleKey + ':read') &&
                               this.selectedAbilities.includes(moduleKey + ':write');
                    },

                    toggleModule(moduleKey) {
                        const readAbility = moduleKey + ':read';
                        const writeAbility = moduleKey + ':write';

                        if (this.isModuleFull(moduleKey)) {
                            this.selectedAbilities = this.selectedAbilities.filter(
                                a => a !== readAbility && a !== writeAbility
                            );
                        } else {
                            if (!this.selectedAbilities.includes(readAbility)) {
                                this.selectedAbilities.push(readAbility);
                            }
                            if (!this.selectedAbilities.includes(writeAbility)) {
                                this.selectedAbilities.push(writeAbility);
                            }
                        }
                    },

                    selectAllAbilities() {
                        const all = [];
                        this.availableModules.forEach(mod => {
                            all.push(mod.key + ':read');
                            all.push(mod.key + ':write');
                        });
                        this.selectedAbilities = all;
                    },

                    deselectAllAbilities() {
                        this.selectedAbilities = [];
                    },

                    storeKey(params, { resetForm, setErrors }) {
                        if (this.permissionType === 'custom' && this.selectedAbilities.length === 0) {
                            this.$emitter.emit('add-flash', {
                                type: 'warning',
                                message: 'Selecione pelo menos uma permissão ou marque Acesso Total.'
                            });
                            return;
                        }

                        this.isProcessing = true;

                        this.$axios.post("{{ route('admin.settings.api_keys.store') }}", {
                            name: this.keyName || params.name,
                            user_id: this.selectedUserId,
                            permission_type: this.permissionType,
                            abilities: this.permissionType === 'custom' ? this.selectedAbilities : ['*'],
                        })
                        .then(response => {
                            this.isProcessing = false;
                            this.plainTextToken = response.data.plain_text_token;
                            this.createdKeyInfo = response.data.token;

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
                        this.createdKeyInfo = null;
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
