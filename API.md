| Routes | Nom de la route | Méthodes (HTTP) |
|---|---|---|
| `/api/questions?[tag={name}]` | api_questions_browse | GET |
| `/api/questions/{id}` | api_questions_read | GET |
| `/api/questions` | api_questions_add | POST |
| `/api/questions/{id}/answers`| api_answers_add | POST |
| `/api/tags` | api_tags_browse | GET |
|---|---|---|
| `/api/questions/{id}/answers/{id}`| api_answers_delete | DELETE |



| Routes | Controller | ->méthode() |
|---|---|---|
| `/api/questions?[tag={name}]` | App\Controller\Api\QuestionController | browse() |
| `/api/questions/{id}` | App\Controller\Api\QuestionController | read() |
| `/api/questions` | App\Controller\Api\QuestionController | add() |
| `/api/questions/{id}/answers`| App\Controller\Api\AnswerController | add() |
| `/api/tags` | App\Controller\Api\TagController | browse() |
|---|---|---|
| `/api/questions/{id}/answers/{id}`| App\Controller\Api\AnswerController | delete() |