# Krayin API Keys & Personal Access Tokens Plugin

Um plugin elegante e plug-and-play para o **Krayin CRM** que permite aos usuários gerenciarem suas próprias **Chaves de API (Personal Access Tokens)** diretamente pelo painel administrativo, com controle granular de login associado e escopos de permissão por módulo.

---

## 🚀 Funcionalidades

- **Interface Visual Nativa**: Integrado ao design system e tema escuro/claro do Krayin CRM.
- **Seleção de Login / Usuário**: Administradores podem emitir chaves atreladas a usuários específicos ou contas de serviço, herdando automaticamente as regras de papéis e visibilidade (`view_permission`) daquele usuário.
- **Controle de Escopos e Permissões (Abilities)**:
  - Opção de **Acesso Total** (`*`) ou **Personalizado**.
  - Permissões divididas por módulo: **Leads**, **Contatos**, **Cotações**, **Produtos**, **Atividades**, **E-mails**, **Configurações**.
  - Controle de **Leitura (GET)** e **Escrita (POST, PUT, DELETE)** por módulo.
- **Middleware de Bloqueio em Tempo Real**: Valida as habilidades da chave diretamente nas rotas da REST API (`/api/v1/*`), retornando `403 Forbidden` com feedback claro caso a operação não seja permitida para a chave.
- **Cópia Segura Única**: Exibe o token gerado apenas uma vez com botão de cópia rápida.
- **Revogação Imediata**: Botão de exclusão/revogação de chaves em tempo real no DataGrid.
- **Controle de Acesso Administrativo (ACL)**: Permissões configuráveis por perfil de usuário.
- **100% Compatível com Krayin REST API**: As chaves geradas usam o Laravel Sanctum nativo do Krayin (`Authorization: Bearer <token>`).

---

## 📦 Instalação em qualquer Krayin CRM

### Via Composer

Adicione o repositório ou instale diretamente:

```bash
composer require expertsa/krayin-api-keys
```

Se estiver instalando a partir do repositório Git:

```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/expertsa/krayin-api-keys.git"
    }
]
```

E em seguida:
```bash
composer require expertsa/krayin-api-keys:*
php artisan optimize:clear
```

---

## 🔑 Como Utilizar

1. Acesse o painel administrativo do seu Krayin CRM.
2. Navegue até **Configurações > Chaves de API** (`/admin/settings/api-keys`).
3. Clique em **"+ Criar Chave de API"**.
4. Defina:
   - **Nome da Aplicação** (ex: *Automação n8n Leads*).
   - **Usuário Associado** (escolha o usuário/vendedor ou conta bot para herdar sua identidade e regras de acesso).
   - **Permissões**: Escolha **Acesso Total** ou marque os módulos e operações permitidas (ex: apenas Leitura e Escrita em *Leads*).
5. Clique em **Gerar Chave**.
6. Copie a chave exibida no modal.
7. Utilize a chave em suas ferramentas externas no cabeçalho HTTP:
   ```http
   Authorization: Bearer <SUA_CHAVE_GERADA>
   ```

---

## 🛡️ Licença

Distribuído sob a licença MIT. Desenvolvido por [ExpertSA](https://expertsa.com.br).
