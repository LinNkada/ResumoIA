# Gerador de Resumos com IA

**API de IA Utilizada**: Hugging Face Inference API (modelo facebook/bart-large-cnn para resumo de texto).

**Descrição**: Aplicação web em PHP que gera resumos de textos acadêmicos usando IA externa via requisição HTTP.

**Como Rodar/Testar**:
1. Configure um servidor PHP/MySQL (ex.: XAMPP).
2. Crie o banco de dados e tabela conforme acima.
3. Clone o repositório e acesse `index.php` no navegador.
4. Insira um texto e clique em "Gerar Resumo".

**Explicação do Código**:
- `index.php`: Contém formulário HTML, lógica PHP para requisição cURL à API Hugging Face, e salvamento no MySQL.
- Método de consumo: Requisição HTTP POST via cURL em PHP, enviando JSON com o texto e parâmetros.
- Histórico salvo em tabela MySQL para perguntas (textos) e respostas (resumos).
