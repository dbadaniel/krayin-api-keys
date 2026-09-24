<?php

return [
    'acl' => [
        'title' => 'Chaves de API',
    ],

    'admin' => [
        'menu' => [
            'api-keys' => 'Chaves de API',
        ],

        'api-keys' => [
            'index' => [
                'title'       => 'Chaves de API / Tokens Pessoais',
                'create-btn'  => 'Criar Chave de API',
                'description' => 'Gerencie seus tokens de acesso para autenticar chamadas na REST API do Krayin sem precisar expor sua senha pessoal.',
            ],

            'create' => [
                'title'            => 'Gerar Nova Chave de API',
                'name'             => 'Nome da Chave / Aplicação',
                'name-placeholder' => 'Ex: Automação n8n, Integração Hotmart, Script Zapier',
                'user'             => 'Usuário / Login Associado',
                'user-help'        => 'A chave operará sob a identidade e as permissões deste usuário.',
                'permissions'      => 'Permissões da Chave de API',
                'all-permissions'  => 'Acesso Total (Todas as operações permitidas)',
                'custom-permissions' => 'Personalizar Permissões (Limitar escopos)',
                'select-all'       => 'Selecionar Todos',
                'deselect-all'     => 'Desmarcar Todos',
                'read'             => 'Leitura (GET)',
                'write'            => 'Escrita (POST/PUT/DELETE)',
                'save-btn'         => 'Gerar Chave',
                'cancel-btn'       => 'Cancelar',
                'modules'          => [
                    'leads'         => 'Leads (Oportunidades)',
                    'contacts'      => 'Contatos (Pessoas e Organizações)',
                    'quotes'        => 'Cotações (Propostas)',
                    'products'      => 'Produtos',
                    'activities'    => 'Atividades',
                    'mails'         => 'E-mails',
                    'settings'      => 'Configurações',
                    'configuration' => 'Configurações Avançadas',
                ],
            ],

            'created' => [
                'title'            => 'Chave de API Gerada com Sucesso!',
                'warning'          => 'Copie e salve sua chave agora em um local seguro. Por motivos de segurança, ela NÃO será exibida novamente!',
                'copy-btn'         => 'Copiar Chave',
                'copied'           => 'Copiado para a área de transferência!',
                'close-btn'        => 'Já copiei a chave',
                'how-to-use'       => 'Como usar esta chave:',
                'how-to-use-desc'  => 'Adicione este cabeçalho HTTP nas requisições da sua ferramenta (ex: n8n, Postman, Webhooks):',
            ],

            'create-success' => 'Chave de API criada com sucesso.',
            'delete-success' => 'Chave de API revogada com sucesso.',
            'not-found'      => 'Chave de API não encontrada ou já revogada.',
            'unauthorized'   => 'Esta Chave de API não possui permissão (:ability) para realizar esta operação.',
        ],

        'datagrid' => [
            'id'           => 'ID',
            'name'         => 'Nome da Chave',
            'user'         => 'Usuário / Login',
            'permissions'  => 'Permissões',
            'all'          => 'Acesso Total',
            'custom'       => 'Personalizado (:count escopos)',
            'last-used-at' => 'Último Uso',
            'created-at'   => 'Data de Criação',
            'never-used'   => 'Nunca utilizado',
            'delete'       => 'Revogar Chave',
        ],
    ],
];
