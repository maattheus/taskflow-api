# 🧠 TaskFlow — arquitetura limpa com Laravel

O **TaskFlow** é um projeto desenvolvido com foco em **boas práticas de arquitetura backend**, utilizando o framework **Laravel**.  
A estrutura foi planejada para refletir padrões usados em sistemas de larga escala, garantindo **clareza, testabilidade e desacoplamento entre camadas**.

---

## 🧩 Estrutura de Arquitetura

O sistema foi refatorado seguindo princípios de **Clean Architecture** e **SOLID**, dividindo responsabilidades entre camadas bem definidas:

```
Controller → Request → DTO → Use Case → Repository → Model
```

### 🔹 Controller

Responsável apenas por receber as requisições HTTP, converter dados para DTOs e acionar os casos de uso.  
Nenhuma lógica de negócio é executada nesta camada.

### 🔹 DTO (Data Transfer Object)

Objetos imutáveis responsáveis por transportar dados entre camadas sem expor diretamente o `Request` do Laravel.  
Garantem segurança, clareza e desacoplamento do framework.

Exemplos:

-   `TaskDTO` — criação e atualização de tarefas
-   `MoveTaskDTO` — movimentação de tarefas entre boards

### 🔹 Use Cases

Camada onde vivem as **regras de negócio reais**.  
Cada ação do domínio é representada por um caso de uso independente, por exemplo:

-   `MoveTaskToBoard` — responsável por mover uma tarefa entre quadros e aplicar validações de negócio.

Essa separação segue o padrão de grandes sistemas, facilitando testes unitários e manutenção.

### 🔹 Repository Pattern

Camada de acesso a dados desacoplada do Eloquent.  
Cada entidade possui uma interface e uma implementação concreta:

-   `TaskInterface` / `TaskRepository`
-   `BoardInterface` / `BoardRepository`

Essa abordagem permite trocar o ORM, simular dados em testes ou adicionar cache sem alterar a camada de domínio.

---

## ⚙️ Tratamento Global de Erros

O arquivo `app/Exceptions/Handler.php` foi reescrito para padronizar respostas de erro em formato JSON, com:

-   `status`, `message`, `error_code` e `trace_id` únicos por requisição.
-   Exceções específicas de domínio (`InvalidBoardMoveException`) são capturadas automaticamente.

Exemplo:

```json
{
    "status": "error",
    "message": "Task not found.",
    "error_code": "DOMAIN_ERROR",
    "trace_id": "9b3c55a6-ef9f-4f56-bb22-7c57a03c7f2d"
}
```

---

## 💡 Benefícios Técnicos

-   **Separação de responsabilidades** (cada camada faz apenas uma coisa)
-   **Código desacoplado do Laravel** — dominado por regras de negócio puras
-   **Testabilidade** — fácil criar testes unitários de Use Cases e Repositories
-   **Escalabilidade** — novos módulos e fluxos podem ser adicionados sem quebrar código existente
-   **Boas práticas corporativas** — estrutura semelhante a sistemas de bancos e fintechs

---

## 🧭 Tecnologias e Conceitos Aplicados

-   **Laravel 10+**
-   **PHP 8.2**
-   **Clean Architecture**
-   **Repository Pattern**
-   **DTOs e Use Cases**
-   **Injeção de Dependência (IoC Container)**
-   **Tratamento de erros global e padronizado**
-   **Logs estruturados**

---

## 🧰 Próximos passos

-   Adicionar testes unitários com **Pest** ou **PHPUnit**
-   Implementar eventos e jobs assíncronos (ex: `TaskCreated`, `SendNotificationJob`)
-   Adicionar cache e métricas (ex: Redis, Prometheus)

---

## 🧑‍💻 Autor

**Matheus Oliveira**  
Desenvolvedor Full Stack (Laravel + Angular)  
📍 Pindamonhangaba/SP  
🔗 [github.com/maattheus](https://github.com/maattheus)
🔗 [linkedin.com/matheus-oliveira](https://www.linkedin.com/in/matheus-oliveira-087730177/)
