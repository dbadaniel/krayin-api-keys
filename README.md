# Krayin API Keys & Personal Access Tokens Plugin

Um plugin elegante e plug-and-play para o **Krayin CRM** que permite aos usuários gerenciarem suas próprias **Chaves de API (Personal Access Tokens)** diretamente pelo painel administrativo, sem precisar expor logins/senhas ou acessar o terminal.

---

## 🚀 Funcionalidades

- **Interface Visual Nativa**: Integrado ao design system e tema escuro/claro do Krayin CRM.
- **Auto-Atendimento (Self-Service)**: Usuários podem gerar, nomear e copiar tokens em segundos.
- **Cópia Segura Única**: Exibe o token gerado apenas uma vez com botão de cópia rápida.
- **Revogação Imediata**: Botão de exclusão/revogação de chaves em tempo real no DataGrid.
- **Controle de Acesso (ACL)**: Permissões configuráveis por perfil de usuário.
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
4. Dê um nome descritivo para a integração (ex: *n8n Automação*, *Integração Hotmart*, *Postman Dev*).
5. Clique em **Gerar**.
6. Copie a chave exibida no modal.
7. Utilize a chave em suas ferramentas externas no cabeçalho HTTP:
   ```http
   Authorization: Bearer <SUA_CHAVE_GERADA>
   ```

---

## 🛡️ Licença

Distribuído sob a licença MIT. Desenvolvido por [ExpertSA](https://expertsa.com.br).
