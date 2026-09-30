-- Adiciona a situação das tarefas e o perfil (permissão) dos usuários.
USE tarefas;

ALTER TABLE tasks
    ADD COLUMN situacao ENUM('pendente', 'em_andamento', 'concluida') NOT NULL DEFAULT 'pendente' AFTER descricao;

ALTER TABLE users
    ADD COLUMN perfil ENUM('usuario', 'admin') NOT NULL DEFAULT 'usuario' AFTER senha;

-- Administrador inicial
UPDATE users SET perfil = 'admin' WHERE email = 'admin@admin';
