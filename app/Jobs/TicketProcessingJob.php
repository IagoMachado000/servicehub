<?php

namespace App\Jobs;

use App\Models\Ticket;
use App\Notifications\TicketProcessedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TicketProcessingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;

    public function __construct(
        protected Ticket $ticket
    ) {}

    public function handle(): void
    {
        // Texto base para análise
        $textToAnalyze = "";

        // CASO 1: TEM ANEXO (Prioridade)
        if ($this->ticket->attachment_path && Storage::exists($this->ticket->attachment_path)) {
            $content = Storage::get($this->ticket->attachment_path);
            $extension = strtolower(pathinfo($this->ticket->attachment_path, PATHINFO_EXTENSION));

            if ($extension === 'json') {
                // Tenta extrair valores úteis do JSON ou converte tudo para string
                $json = json_decode($content, true);
                // Se for JSON, transformamos em string legível para nossa "IA" ler
                $textToAnalyze = json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } else {
                // TXT ou Logs
                $textToAnalyze = $content;
            }
        }
        // CASO 2: NÃO TEM ANEXO (Fallback)
        else {
            // Analisa o Título e a Descrição que o usuário digitou
            $textToAnalyze = $this->ticket->title . "\n" . ($this->ticket->detail->description ?? '');
        }

        // --- EXECUTA A "PSEUDO-INTELIGÊNCIA" ---

        $analysis = $this->analyzeContent($textToAnalyze);

        // Salva os dados enriquecidos
        $this->ticket->detail()->update([
            'summary'          => $analysis['summary'],
            'keywords'         => $analysis['keywords'],
            'sentiment_score'  => $analysis['score'],
            'processed_at'     => now(),
        ]);

        // Atualiza status e notifica
        $this->ticket->update(['status' => 'open']);
        $this->ticket->user->notify(new TicketProcessedNotification($this->ticket));
    }

    /**
     * Simula uma IA analisando o texto
     */
    private function analyzeContent(string $text): array
    {
        // 1. Gerar Resumo (Pega os primeiros 200 caracteres)
        $summary = Str::limit(strip_tags($text), 200);

        // 2. Extrair Palavras-Chave (Keywords)
        $keywords = [];
        $textLower = mb_strtolower($text);

        // Dicionário de termos técnicos para buscar
        $commonTerms = ['erro', 'timeout', 'banco', 'login', 'senha', 'api', 'json', 'servidor', 'lento', 'bug'];

        foreach ($commonTerms as $term) {
            if (str_contains($textLower, $term)) {
                $keywords[] = $term;
            }
        }

        // Se não achou nada, coloca uma tag padrão
        if (empty($keywords)) {
            $keywords[] = 'geral';
        }

        // 3. Calcular Sentimento (-1.0 a +1.0)
        // Lógica: Palavras ruins diminuem o score, boas aumentam.
        $score = 0;

        $negativeWords = ['erro', 'falha', 'failed', 'crítico', 'bug', 'travou', 'ruim', 'não funciona', 'exception'];
        $positiveWords = ['sugestão', 'melhoria', 'elogio', 'rápido', 'bom'];

        foreach ($negativeWords as $word) {
            if (str_contains($textLower, $word)) $score -= 0.5;
        }

        foreach ($positiveWords as $word) {
            if (str_contains($textLower, $word)) $score += 0.5;
        }

        // Normalizar entre -1 e 1
        $score = max(-1.0, min(1.0, $score));

        return [
            'summary' => $summary,
            'keywords' => $keywords,
            'score' => $score
        ];
    }
}
