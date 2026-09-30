-- Anexos das tarefas. O arquivo fica em storage/anexos/ com um nome aleatório (coluna arquivo).
USE tarefas;

CREATE TABLE IF NOT EXISTS task_anexos (
    id INT NOT NULL AUTO_INCREMENT,
    tarefa INT NOT NULL,
    usuario INT NULL,
    nome_original VARCHAR(255) NOT NULL,
    arquivo VARCHAR(64) NOT NULL,
    mime VARCHAR(100) NOT NULL,
    tamanho INT UNSIGNED NOT NULL,
    data_inclusao DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY task_anexos_arquivo (arquivo),
    KEY FK_ANEXO_TASK (tarefa),
    KEY FK_ANEXO_USER (usuario),
    CONSTRAINT FK_ANEXO_TASK FOREIGN KEY (tarefa) REFERENCES tasks (id) ON DELETE CASCADE,
    CONSTRAINT FK_ANEXO_USER FOREIGN KEY (usuario) REFERENCES users (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
