# Tarefas

Lista de tarefas com login, anexos e painel de administração, em PHP puro + MySQL + Bootstrap.

## Estrutura

```
tarefas/
├── .htaccess          # redireciona tudo para public/ e define o limite de upload
├── config/            # config.php (credenciais, fora do git) e config.example.php
├── database/          # schema.sql (estrutura completa), migrations/ e seed.php (dados de exemplo)
├── storage/anexos/    # arquivos enviados (fora do git e inacessível pelo navegador)
├── src/               # lógica da aplicação (não acessível pelo navegador)
│   ├── bootstrap.php  # carregado por todo arquivo de public/
│   ├── helpers.php    # e(), view(), render(), redirecionar(), input()...
│   ├── database.php   # db(): conexão PDO
│   ├── auth.php       # sessão do usuário e personificação
│   ├── permissoes.php # perfis e permissões (pode(), pode_ver_tarefa(), pode_alterar_tarefa())
│   ├── tarefas.php    # situações das tarefas
│   ├── anexos.php     # upload, validação e download de anexos
│   ├── usuarios.php   # regras do painel de usuários
│   ├── csrf.php       # token contra CSRF
│   ├── api.php        # respostas JSON e iniciar_api()
│   ├── validacao.php  # regras de validação
│   └── repositorios/  # SQL de cada tabela (usuarios, tarefas, anexos)
├── views/             # HTML
│   ├── layouts/       # app.php (com cabeçalho) e auth.php (login/cadastro)
│   ├── partials/      # head, navbar, faixa de personificação, scripts
│   └── auth/, tarefas/, conta/, usuarios/, erros/
└── public/            # única pasta exposta
    ├── *.php          # páginas: index, login, cadastro, conta, usuarios, anexo (download), logout
    ├── api/           # endpoints JSON (sempre POST + token CSRF)
    └── assets/        # css/, js/ e img/ (favicon)
```

## Como rodar

1. Crie o banco com `database/schema.sql` (ou aplique os arquivos de `database/migrations/` em ordem).
2. Copie `config/config.example.php` para `config/config.php` e preencha as credenciais.
3. Opcional: `php database/seed.php` cria usuários e tarefas de exemplo (senha `123456`).
4. Acesse pelo Apache (precisa de `mod_rewrite` e `AllowOverride All`).

### Anexos: permissão de escrita

O Apache (usuário `www-data`) precisa poder gravar em `storage/anexos/`. No Ubuntu o serviço
roda com `ProtectHome=read-only`, que deixa todo o `/home` somente leitura para o Apache.
Para liberar só essa pasta:

```bash
sudo systemctl edit apache2
# adicione:
# [Service]
# ReadWritePaths=/home/abraaoc/apache/projetos/tarefas/storage
sudo systemctl restart apache2
```

Outra opção: apontar `anexos_dir` no `config.php` para uma pasta fora do `/home`.

## Permissões

Cada usuário tem um `perfil` (`usuario` ou `admin`). As permissões de cada perfil ficam em
`src/permissoes.php`:

| Permissão               | O que libera                                 |
|-------------------------|----------------------------------------------|
| `tarefas.ver_todas`     | ver as tarefas (e anexos) de todos           |
| `tarefas.editar_todas`  | editar tarefas e anexos de qualquer usuário  |
| `tarefas.excluir_todas` | excluir tarefas de qualquer usuário          |
| `usuarios.gerenciar`    | painel de usuários (criar, editar, excluir)  |
| `usuarios.personificar` | navegar como outro usuário                   |

Sem permissão extra, cada usuário só vê e altera as próprias tarefas.
Um administrador não pode excluir a si mesmo, mudar o próprio perfil nem personificar outro administrador.
Durante a personificação não é possível alterar os dados nem a senha da conta personificada.
Início e fim de cada personificação ficam registrados no log de erros do Apache.
