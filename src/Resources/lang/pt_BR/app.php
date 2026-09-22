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
                'save-btn'         => 'Gerar Chave',
                'cancel-btn'       => 'Cancelar',
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
        ],

        'datagrid' => [
            'id'           => 'ID',
            'name'         => 'Nome da Chave',
            'user'         => 'Criado Por',
            'last-used-at' => 'Último Uso',
            'created-at'   => 'Data de Criação',
            'never-used'   => 'Nunca utilizado',
            'delete'       => 'Revogar Chave',
        ],
    ],
];
