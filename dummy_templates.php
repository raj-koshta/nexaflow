
use App\Models\AiPromptTemplate;

AiPromptTemplate::truncate();

AiPromptTemplate::create([
    'name' => 'Generate Follow-Up Email',
    'description' => 'A template to generate a professional follow-up email after a client meeting.',
    'system_prompt' => 'You are an expert sales representative. Your goal is to write a polite, concise, and professional follow-up email. Do not include subject lines. Keep it under 150 words.',
    'user_prompt' => 'Please write a follow-up email to the client named [Client Name] regarding our recent meeting about [Project Name]. Highlight these key points: [Key Points].',
    'is_active' => true,
]);

AiPromptTemplate::create([
    'name' => 'Ticket Resolution Summary',
    'description' => 'Automatically summarize the steps taken to resolve a support ticket.',
    'system_prompt' => 'You are a Senior Technical Support Engineer. Summarize the provided ticket thread into a clear, bulleted list explaining what the issue was and how it was resolved.',
    'user_prompt' => 'Ticket Title: [Ticket Title]\nThread History:\n[Ticket Thread]\n\nPlease generate a concise resolution summary.',
    'is_active' => true,
]);

AiPromptTemplate::create([
    'name' => 'Project Kickoff Proposal',
    'description' => 'Draft a formal project kickoff proposal outline based on raw meeting notes.',
    'system_prompt' => 'You are a professional Project Manager. Structure the raw notes into a formal project proposal outline including Objectives, Scope, Timeline, and Resource Requirements.',
    'user_prompt' => 'Client: [Client Name]\nRaw Notes:\n[Meeting Notes]\n\nPlease generate the proposal outline.',
    'is_active' => true,
]);

