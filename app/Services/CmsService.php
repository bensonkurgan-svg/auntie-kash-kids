<?php
namespace App\Services;
use App\Models\CmsPage;

class CmsService
{
    public static function homepage(): array
    {
        try {
            $page = CmsPage::where('page_key','homepage')->where('status','APPROVED')->first();
            if ($page && $page->content) return array_merge(self::defaultHomepage(), $page->content);
        } catch (\Throwable $e) {}
        return self::defaultHomepage();
    }

    public static function about(): array
    {
        try {
            $page = CmsPage::where('page_key','about')->where('status','APPROVED')->first();
            if ($page && $page->content) return array_merge(self::defaultAbout(), $page->content);
        } catch (\Throwable $e) {}
        return self::defaultAbout();
    }

    public static function defaultHomepage(): array
    {
        return [
            'hero' => [
                'headline' => 'Where Young Minds Learn, Create, Sing, Speak & Shine!',
                'subheading' => 'Live online enrichment programs that build confidence, spark creativity, develop skills, and prepare children for a bright future.',
                'primaryCTA' => 'Book a Free Discovery Call',
                'secondaryCTA' => 'Explore Programs',
            ],
            'aboutPreview' => ['blurb' => 'Auntie Kash Kids is a global children\'s learning platform celebrating African heritage while delivering world-class enrichment education for children aged 5–16. Every child is gifted. Every child can shine.'],
            'footer' => ['copyright' => '© 2026 Auntie Kash Kids Academy. All rights reserved.', 'contactEmail' => 'hello@auntiekashkids.com'],
        ];
    }

    public static function defaultAbout(): array
    {
        return [
            'headline' => 'Welcome to Auntie Kash Kids',
            'tagline' => 'Where Young Minds Learn, Create, Sing, Speak & Shine™',
            'intro' => 'At Auntie Kash Kids, we believe that every child is gifted and every child has the potential to shine.',
            'mission' => 'We provide live online enrichment programs, books, workshops, and creative activities that encourage children to explore their talents.',
            'vision' => 'We envision a world where children from different countries, cultures, and backgrounds can come together to learn, create, communicate, and grow.',
            'founderName' => 'Kasham Keltuma',
            'founderTitle' => 'Founder & CEO — "Auntie Kash"',
            'founderBio' => 'Kasham Keltuma, affectionately known as "Auntie Kash," is an author, storyteller, filmmaker, communication professional, and child development practitioner.',
            'qualifications' => ['BA in Theatre & Communication Arts','Diploma in Mass Communication','Postgraduate Diploma in Filmmaking','Community Developmental Service Worker Diploma with Honours'],
            'promises' => ['Encourage confidence','Inspire creativity','Celebrate individuality','Promote kindness and respect','Support lifelong learning','Help every child discover their unique gifts'],
            'programFocus' => [
                ['icon'=>'🇬🇧','label'=>'English Communication & Confidence'],['icon'=>'📚','label'=>'Literacy & Reading'],
                ['icon'=>'🎤','label'=>'Public Speaking & Leadership'],['icon'=>'✍️','label'=>'Creative Writing & Storytelling'],
                ['icon'=>'🎨','label'=>'Art & Creativity'],['icon'=>'🎵','label'=>'Music & Performance'],
                ['icon'=>'🌍','label'=>'Cultural Awareness'],['icon'=>'💡','label'=>'Entrepreneurship'],
                ['icon'=>'🔬','label'=>'STEM & Future Skills'],['icon'=>'🧠','label'=>'Confidence & Personal Development'],
                ['icon'=>'🇫🇷','label'=>'French Language Learning'],['icon'=>'🇨🇳','label'=>'Mandarin Language Learning'],
            ],
        ];
    }
}
