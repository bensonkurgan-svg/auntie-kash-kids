<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\TutorProfile;
use App\Models\Course;
use App\Models\Module;
use App\Models\Lesson;
use App\Models\Student;
use App\Models\Enrollment;
use App\Models\ContentPost;
use App\Models\CmsPage;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Idempotent — skip only if seeding fully completed last time.
        // We check for courses (seeded near the end) rather than just the CEO user,
        // so a half-finished seed can still complete on the next run.
        if (\App\Models\Course::count() > 0 && User::where('email','ceo@auntiekash.com')->exists()) {
            $this->command->info('Database already fully seeded — checking shop...');
            $this->seedShop();
            $this->seedLibrary();
            $this->seedActivities();
        $this->seedBooks();
            $this->seedBooks();
            return;
        }

        // Clear any partial data from a previously-interrupted seed so we start clean.
        \App\Models\User::query()->delete();

        // ── Demo users ────────────────────────────────────────────────────
        $ceo = User::create(['name'=>'Kasham Keltuma','email'=>'ceo@auntiekash.com','password'=>Hash::make('DemoPass123'),'role'=>'CEO']);
        $admin = User::create(['name'=>'Admin User','email'=>'admin@auntiekash.com','password'=>Hash::make('DemoPass123'),'role'=>'ADMIN']);
        $tutor1 = User::create(['name'=>'Sarah Johnson','email'=>'tutor1@auntiekash.com','password'=>Hash::make('DemoPass123'),'role'=>'TUTOR']);
        $tutor2 = User::create(['name'=>'David Okonkwo','email'=>'tutor2@auntiekash.com','password'=>Hash::make('DemoPass123'),'role'=>'TUTOR']);
        $parent1 = User::create(['name'=>'Grace Adeyemi','email'=>'parent1@example.com','password'=>Hash::make('DemoPass123'),'role'=>'PARENT']);
        $parent2 = User::create(['name'=>'James Nwosu','email'=>'parent2@example.com','password'=>Hash::make('DemoPass123'),'role'=>'PARENT']);

        // ── Tutor profiles ────────────────────────────────────────────────
        $tp1 = TutorProfile::create(['user_id'=>$tutor1->id,'bio'=>'Sarah is a passionate literacy and creative writing educator with 8 years of experience working with children aged 5-14. She specialises in building reading confidence and sparking the joy of storytelling.','specialties'=>['Reading','Creative Writing','Storytelling','Literacy'],'rating'=>4.9,'review_count'=>47,'phone'=>'+44 7700 900123','work_email'=>'sarah@auntiekashkids.com']);
        $tp2 = TutorProfile::create(['user_id'=>$tutor2->id,'bio'=>'David is a performing arts and communications specialist with a background in theatre and public speaking coaching. He brings energy, humour, and expertise to every session.','specialties'=>['Public Speaking','Music','Leadership','Drama'],'rating'=>4.8,'review_count'=>35,'phone'=>'+44 7700 900456','work_email'=>'david@auntiekashkids.com']);

        // ── 12 courses ───────────────────────────────────────────────────
        $courses = [
            ['slug'=>'young-readers-club',       'title'=>'Young Readers Club',       'icon'=>'📖','price'=>49.99, 'tp'=>$tp1, 'desc'=>'Ignite a lifelong love of reading with guided stories, comprehension activities, and literary exploration across genres and cultures.'],
            ['slug'=>'young-authors-club',        'title'=>'Young Authors Club',        'icon'=>'✍️', 'price'=>49.99, 'tp'=>$tp1, 'desc'=>'Develop storytelling skills, explore creative writing techniques, and unleash the writer within through poetry, fiction, and journaling.'],
            ['slug'=>'public-speaking-club',      'title'=>'Public Speaking Club',      'icon'=>'🎤','price'=>54.99, 'tp'=>$tp2, 'desc'=>'Build confidence and powerful communication skills through structured speaking activities, debates, presentations, and performance techniques.'],
            ['slug'=>'storytelling-club',         'title'=>'Storytelling Club',         'icon'=>'⭐','price'=>49.99, 'tp'=>$tp1, 'desc'=>'Explore the ancient art of storytelling rooted in African oral traditions, narrative techniques, and the power of voice and imagination.'],
            ['slug'=>'art-creativity-club',       'title'=>'Art & Creativity Club',     'icon'=>'🎨','price'=>44.99, 'tp'=>$tp1, 'desc'=>'Unleash imagination through drawing, painting, crafts, and creative expression inspired by African artistic traditions and global art forms.'],
            ['slug'=>'music-rhythm-club',         'title'=>'Music & Rhythm Club',       'icon'=>'🎵','price'=>54.99, 'tp'=>$tp2, 'desc'=>'Discover rhythm, melody, instruments, and the joy of music through interactive lessons celebrating African music and global musical traditions.'],
            ['slug'=>'future-leaders-club',       'title'=>'Future Leaders Club',       'icon'=>'👥','price'=>59.99, 'tp'=>$tp2, 'desc'=>'Develop leadership skills, emotional intelligence, teamwork, and entrepreneurial thinking to become the confident leaders of tomorrow.'],
            ['slug'=>'confidence-builders-club',  'title'=>'Confidence Builders Club',  'icon'=>'💪','price'=>49.99, 'tp'=>$tp1, 'desc'=>'Build self-esteem, resilience, positive self-image, and personal development skills through engaging activities and guided self-discovery.'],
            ['slug'=>'english-communication',     'title'=>'English Communication',     'icon'=>'🇬🇧','price'=>54.99, 'tp'=>$tp1, 'desc'=>'Develop English language fluency, vocabulary, pronunciation, and real-world communication skills for children of all proficiency levels.'],
            ['slug'=>'cultural-awareness',        'title'=>'Cultural Awareness',        'icon'=>'🌍','price'=>44.99, 'tp'=>$tp2, 'desc'=>'Explore African heritage, traditions, and global cultures to develop cultural pride, empathy, and a sense of belonging in the world.'],
            ['slug'=>'french-language-learning',  'title'=>'French Language Learning',  'icon'=>'🇫🇷','price'=>54.99, 'tp'=>$tp1, 'desc'=>'Learn French through songs, stories, games, and conversation from beginner to intermediate level in a fun, engaging environment.'],
            ['slug'=>'stem-future-skills',        'title'=>'STEM & Future Skills',      'icon'=>'🔬','price'=>59.99, 'tp'=>$tp2, 'desc'=>'Explore science, technology, engineering, and mathematics through hands-on projects, critical thinking, and creative problem-solving.'],
        ];

        foreach ($courses as $c) {
            $course = Course::create([
                'slug'=>$c['slug'],'title'=>$c['title'],'description'=>$c['desc'],
                'icon'=>$c['icon'],'price'=>$c['price'],
                'tutor_profile_id'=>$c['tp']->id,'is_published'=>true,
            ]);
            // 3 modules per course with 3 lessons each
            for ($m = 1; $m <= 3; $m++) {
                $module = Module::create(['course_id'=>$course->id,'title'=>"Module {$m}: ".['Introduction','Core Skills','Advanced Practice'][$m-1],'order'=>$m]);
                for ($l = 1; $l <= 3; $l++) {
                    Lesson::create(['module_id'=>$module->id,'title'=>"Lesson {$l}: Session ".($l),'content'=>"<p>Welcome to this lesson. In this session we will explore the key concepts of {$course->title}, building on what we have learned and taking your skills to the next level.</p><p>This is a live interactive session with your tutor. Please come prepared with your materials and an open, curious mind!</p>",'order'=>$l]);
                }
            }
        }

        // ── Students & enrollments ────────────────────────────────────────
        $child1 = Student::create(['parent_id'=>$parent1->id,'name'=>'Amara Adeyemi','age'=>10]);
        $child2 = Student::create(['parent_id'=>$parent1->id,'name'=>'Kofi Adeyemi','age'=>8]);
        $child3 = Student::create(['parent_id'=>$parent2->id,'name'=>'Zara Nwosu','age'=>12]);

        $course1 = Course::where('slug','young-readers-club')->first();
        $course2 = Course::where('slug','public-speaking-club')->first();
        $course3 = Course::where('slug','art-creativity-club')->first();

        Enrollment::create(['student_id'=>$child1->id,'course_id'=>$course1->id,'user_id'=>$parent1->id,'status'=>'ACTIVE','progress'=>65]);
        Enrollment::create(['student_id'=>$child1->id,'course_id'=>$course2->id,'user_id'=>$parent1->id,'status'=>'ACTIVE','progress'=>30]);
        Enrollment::create(['student_id'=>$child2->id,'course_id'=>$course3->id,'user_id'=>$parent1->id,'status'=>'ACTIVE','progress'=>80]);
        Enrollment::create(['student_id'=>$child3->id,'course_id'=>$course1->id,'user_id'=>$parent2->id,'status'=>'ACTIVE','progress'=>45]);

        // ── Blog posts ────────────────────────────────────────────────────
        $blogPosts = [
            ['title'=>'5 Ways to Build Your Child\'s Confidence at Home','excerpt'=>'Practical, fun activities parents can do every day to help children feel seen, valued, and empowered.','body'=>'<p>Confidence is not something children are born with — it is built, day by day, through positive experiences, encouragement, and the freedom to try new things. Here are five powerful ways you can nurture your child\'s confidence right at home.</p><h2>1. Celebrate effort, not just results</h2><p>When we praise the process — "I love how hard you worked on that!" — rather than only the outcome, children learn that their effort matters. This builds a growth mindset and the courage to keep trying even when things are hard.</p><h2>2. Create a "yes" environment</h2><p>Allow your child to explore, create, and experiment without fear of failure. A home where mistakes are seen as learning opportunities is a home where confidence can grow freely.</p><h2>3. Give them real responsibilities</h2><p>Assigning age-appropriate tasks — setting the table, caring for a plant, helping younger siblings — gives children a genuine sense of capability and belonging.</p><h2>4. Let them make choices</h2><p>Even small decisions build autonomy. Let your child choose what to wear, what snack to have, or which book to read. Feeling in control of small things builds confidence for bigger ones.</p><h2>5. Speak life over them</h2><p>The words we speak to our children become the voice inside their heads. Make your voice one of belief, encouragement, and unconditional love.</p>','category'=>'Parenting','read_time'=>'5 min read','tags'=>['confidence','parenting','child development'],'featured'=>true],
            ['title'=>'Why Storytelling is the Most Powerful Learning Tool for Children','excerpt'=>'Research shows that stories activate more of the brain than any other form of learning. Here is why storytelling belongs at the heart of every child\'s education.','body'=>'<p>Long before written language, human beings shared knowledge through stories. From the ancient griots of West Africa to the storytellers of ancient Greece, narrative has always been our most powerful vehicle for transmitting wisdom, values, and identity.</p><p>Modern neuroscience now confirms what our ancestors knew intuitively: stories activate not just the language centres of the brain, but the sensory, motor, and emotional centres too. When a child hears a great story, their entire brain lights up.</p><h2>Stories build empathy</h2><p>When children inhabit the perspective of a character — especially one different from themselves — they develop the ability to understand and share the feelings of others. This is the foundation of emotional intelligence.</p><h2>Stories make learning stick</h2><p>Information embedded in a narrative is far more memorable than information presented as isolated facts. Children who learn through stories retain more, understand more deeply, and connect new information to existing knowledge more effectively.</p><h2>African oral tradition and its gifts</h2><p>At Auntie Kash Kids, we draw deeply from the rich African tradition of oral storytelling — a tradition that values community, wisdom, rhythm, and the power of the spoken word. These stories don\'t just entertain; they form identity, build cultural pride, and teach children who they are and where they come from.</p>','category'=>'Education','read_time'=>'7 min read','tags'=>['storytelling','learning','African heritage'],'featured'=>true],
            ['title'=>'How to Help Your Child Fall in Love with Reading','excerpt'=>'A child who loves to read will always have access to the best teachers, the greatest minds, and the most extraordinary adventures. Here is how to nurture that love.','body'=>'<p>Reading is not just an academic skill — it is a superpower. The child who reads widely becomes the adult who thinks clearly, communicates beautifully, and understands the world deeply. But reading cannot be forced; it must be invited.</p><h2>Read together, always</h2><p>The single most powerful thing you can do is read aloud with your child, long after they can read independently. Shared reading builds vocabulary, comprehension, and above all, a warm association between books and love.</p><h2>Follow their interests</h2><p>A child obsessed with dinosaurs who reads dinosaur books is still a reader. Don\'t insist on "good" books — insist on books, full stop. Interest is the engine of literacy.</p><h2>Create a reading environment</h2><p>Books should be visible, accessible, and plentiful in your home. A small shelf at child height, a cosy reading corner, good lighting — these details matter more than we often realise.</p><h2>Visit the library regularly</h2><p>The library is a free, extraordinary gift. Children who grow up visiting libraries grow up understanding that knowledge is abundant, free, and always available to them.</p>','category'=>'Literacy','read_time'=>'6 min read','tags'=>['reading','literacy','parents'],'featured'=>false],
        ];

        foreach ($blogPosts as $post) {
            ContentPost::create([
                'type'=>'BLOG_POST','title'=>$post['title'],'slug'=>str()->slug($post['title']),
                'excerpt'=>$post['excerpt'],'body'=>$post['body'],'tags'=>$post['tags'],
                'status'=>'PUBLISHED','category'=>$post['category'],'read_time'=>$post['read_time'],
                'featured'=>$post['featured'],'author_id'=>$ceo->id,'published_at'=>now(),
            ]);
        }

        // ── Library items ─────────────────────────────────────────────────
        $library = [
            ['title'=>'The Magic of African Folktales — Reading Guide','excerpt'=>'A beautifully illustrated reading guide exploring the richness and wisdom of African folktales for children aged 6-12.','age_range'=>'Ages 6–12','category'=>'Folktales','tags'=>['African heritage','reading','culture']],
            ['title'=>'My Feelings Journal for Kids','excerpt'=>'A guided journaling resource to help children aged 7-14 identify, name, and express their emotions in healthy, creative ways.','age_range'=>'Ages 7–14','category'=>'Emotional Wellbeing','tags'=>['emotions','journaling','wellbeing']],
            ['title'=>'101 Creative Writing Prompts for Young Writers','excerpt'=>'Spark imagination and build writing habits with 101 carefully crafted prompts designed to inspire children at every skill level.','age_range'=>'Ages 8–16','category'=>'Creative Writing','tags'=>['writing','creativity','imagination']],
        ];

        foreach ($library as $item) {
            ContentPost::create([
                'type'=>'LIBRARY_ITEM','title'=>$item['title'],'slug'=>str()->slug($item['title']),
                'excerpt'=>$item['excerpt'],'body'=>'<p>This resource is available to all enrolled families. Download your copy using the button above.</p>',
                'tags'=>$item['tags'],'status'=>'PUBLISHED','age_range'=>$item['age_range'],
                'category'=>$item['category'],'author_id'=>$ceo->id,'published_at'=>now(),
            ]);
        }

        // ── Parent resources ──────────────────────────────────────────────
        $resources = [
            ['title'=>'Parent Handbook — Getting Started with Auntie Kash Kids','excerpt'=>'Everything you need to know about how our programs work, what to expect, and how to support your child\'s learning journey.','category'=>'Getting Started','tags'=>['parents','handbook','onboarding']],
            ['title'=>'Screen Time Guide for Learning — What Works and What Doesn\'t','excerpt'=>'Evidence-based guidance for parents on how to make online learning time effective, focused, and genuinely beneficial for your child.','category'=>'Learning Tips','tags'=>['screen time','online learning','parents']],
        ];

        foreach ($resources as $item) {
            ContentPost::create([
                'type'=>'PARENT_RESOURCE','title'=>$item['title'],'slug'=>str()->slug($item['title']),
                'excerpt'=>$item['excerpt'],'body'=>'<p>This guide is exclusively available to Auntie Kash Kids families. We hope you find it valuable.</p>',
                'tags'=>$item['tags'],'status'=>'PUBLISHED','category'=>$item['category'],
                'author_id'=>$ceo->id,'published_at'=>now(),
            ]);
        }

        // ── CMS homepage default content ──────────────────────────────────
        CmsPage::create([
            'page_key'=>'homepage','status'=>'APPROVED','version'=>1,'created_by'=>$ceo->id,'reviewed_by'=>$ceo->id,
            'approved_at'=>now(),
            'content'=>[
                'hero'=>[
                    'headline'=>'Where Young Minds Learn, Create, Sing, Speak & Shine!',
                    'subheading'=>'Live online enrichment programs that build confidence, spark creativity, develop skills, and prepare children for a bright future.',
                    'primaryCTA'=>'Book a Free Discovery Call',
                    'secondaryCTA'=>'Explore Programs',
                ],
                'aboutPreview'=>['blurb'=>'Auntie Kash Kids is a global children\'s learning platform celebrating African heritage while delivering world-class enrichment education for children aged 5–16. Every child is gifted. Every child can shine.'],
                'footer'=>['copyright'=>'© 2026 Auntie Kash Kids Academy. All rights reserved.','contactEmail'=>'hello@auntiekashkids.com'],
            ],
        ]);

        // ── CMS about default content ─────────────────────────────────────
        CmsPage::create([
            'page_key'=>'about','status'=>'APPROVED','version'=>1,'created_by'=>$ceo->id,'reviewed_by'=>$ceo->id,
            'approved_at'=>now(),
            'content'=>[
                'headline'=>'Welcome to Auntie Kash Kids',
                'tagline'=>'Where Young Minds Learn, Create, Sing, Speak & Shine™',
                'intro'=>'At Auntie Kash Kids, we believe that every child is gifted and every child has the potential to shine. Our mission is to help children build confidence, creativity, communication skills, leadership abilities, and a lifelong love of learning.',
                'mission'=>'We provide live online enrichment programs, books, workshops, and creative activities that encourage children to explore their talents, develop new skills, and become the best versions of themselves.',
                'vision'=>'We envision a world where children from different countries, cultures, and backgrounds can come together to learn, create, communicate, and grow.',
                'founderName'=>'Kasham Keltuma','founderTitle'=>'Founder & CEO — "Auntie Kash"',
                'founderBio'=>'Kasham Keltuma, affectionately known as "Auntie Kash," is an author, storyteller, filmmaker, communication professional, and child development practitioner with a passion for helping children discover their gifts and reach their full potential.',
                'qualifications'=>['BA in Theatre & Communication Arts','Diploma in Mass Communication','Postgraduate Diploma in Filmmaking','Community Developmental Service Worker Diploma with Honours'],
                'promises'=>['Encourage confidence','Inspire creativity','Celebrate individuality','Promote kindness and respect','Support lifelong learning','Help every child discover their unique gifts'],
                'programFocus'=>[
                    ['icon'=>'🇬🇧','label'=>'English Communication & Confidence'],['icon'=>'📚','label'=>'Literacy & Reading'],
                    ['icon'=>'🎤','label'=>'Public Speaking & Leadership'],['icon'=>'✍️','label'=>'Creative Writing & Storytelling'],
                    ['icon'=>'🎨','label'=>'Art & Creativity'],['icon'=>'🎵','label'=>'Music & Performance'],
                    ['icon'=>'🌍','label'=>'Cultural Awareness'],['icon'=>'💡','label'=>'Entrepreneurship'],
                    ['icon'=>'🔬','label'=>'STEM & Future Skills'],['icon'=>'🧠','label'=>'Confidence & Personal Development'],
                    ['icon'=>'🇫🇷','label'=>'French Language Learning'],['icon'=>'🇨🇳','label'=>'Mandarin Language Learning'],
                ],
            ],
        ]);

        $this->command->info('✅ Auntie Kash Kids seeded successfully!');
        $this->seedShop();
        $this->seedLibrary();
        $this->seedActivities();

        $this->command->info('   CEO:    ceo@auntiekash.com / DemoPass123');
        $this->command->info('   Admin:  admin@auntiekash.com / DemoPass123');
        $this->command->info('   Tutor:  tutor1@auntiekash.com / DemoPass123');
        $this->command->info('   Parent: parent1@example.com / DemoPass123');
    }

    private function seedShop(): void
    {
        if (\App\Models\ShopProduct::count() > 0) return;
        \App\Models\ShopProduct::insert([
            ['name'=>'Auntie Kash Kids T-Shirt','description'=>'Soft cotton tee with the colourful Auntie Kash Kids logo. Available in kids and adult sizes.','type'=>'MERCH','price'=>19.99,'currency'=>'USD','is_featured'=>true,'is_active'=>true,'sort_order'=>1,'created_at'=>now(),'updated_at'=>now()],
            ['name'=>'Rainbow Learning Mug','description'=>'Start the day with a smile - our signature rainbow mug for little learners and grown-ups alike.','type'=>'MERCH','price'=>12.99,'currency'=>'USD','is_featured'=>true,'is_active'=>true,'sort_order'=>2,'created_at'=>now(),'updated_at'=>now()],
            ['name'=>'African Folktales for Children','description'=>'A beautiful illustrated collection of African folktales - perfect for bedtime reading.','type'=>'BOOK','price'=>14.99,'currency'=>'USD','external_url'=>'https://www.amazon.com/','is_featured'=>true,'is_active'=>true,'sort_order'=>3,'created_at'=>now(),'updated_at'=>now()],
            ['name'=>'My First Words Activity Book','description'=>'Interactive early-reading activity book celebrating culture and creativity.','type'=>'BOOK','price'=>9.99,'currency'=>'USD','external_url'=>'https://www.amazon.com/','is_featured'=>true,'is_active'=>true,'sort_order'=>4,'created_at'=>now(),'updated_at'=>now()],
        ]);
        $this->command->info('   + Demo shop products added');
    }


    private function seedLibrary(): void
    {
        if (\App\Models\LibraryResource::count() > 0) return;
        \App\Models\LibraryResource::insert([
            ['title'=>'Instructor Handbook','description'=>'Everything you need to know about teaching with Auntie Kash Kids.','category'=>'Getting Started','audience'=>'INSTRUCTOR','content_type'=>'ARTICLE','body'=>'<h2>Welcome</h2><p>This handbook covers our teaching philosophy, expectations, and day-to-day procedures.</p>','is_featured'=>true,'is_published'=>true,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>'Child Safeguarding Policy','description'=>'Our commitment to keeping every child safe.','category'=>'Policies','audience'=>'INSTRUCTOR','content_type'=>'ARTICLE','body'=>'<h2>Safeguarding</h2><p>All instructors must read and follow our safeguarding procedures at all times.</p>','is_featured'=>false,'is_published'=>true,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>'10 Ways to Build Confidence in Your Child','description'=>'Practical, everyday strategies to help your child believe in themselves. By Kasham Keltuma, Founder of Auntie Kash Kids.','category'=>'Confidence Building','audience'=>'PARENT','content_type'=>'ARTICLE','body'=>'<p>10 Ways to Build Confidence in Your Child</p><p>Confidence is one of the greatest gifts we can help our children develop.</p><p>Confident children are more willing to try new things, speak up for themselves, make friends, recover from setbacks, and pursue their goals. Confidence does not mean believing you are better than others. It means believing you are capable of learning, growing, and handling challenges.</p><p>The good news is that confidence can be nurtured.</p><p>Here are ten practical ways parents can help build confidence in their children.</p><h3>1. Let Them Try Things for Themselves</h3><p>It can be tempting to step in and do things for our children, especially when we know it will be quicker or easier.</p><p>However, confidence grows when children discover what they can do on their own.</p><p>Allow them to solve age-appropriate problems, make decisions, and complete tasks independently.</p><p>Every small success builds belief in their abilities.</p><h3>2. Praise Effort, Not Just Results</h3><p>Children need to know that effort matters.</p><p>Rather than focusing only on grades, trophies, or achievements, praise hard work, perseverance, and determination.</p><p>A child who learns that effort is valuable is more likely to keep trying when things become difficult.</p><h3>3. Encourage Them to Try New Things</h3><p>Confidence often grows outside a child\'s comfort zone.</p><p>Sports, arts, music, reading clubs, public speaking, leadership activities, and other experiences help children discover strengths they never knew they had.</p><p>Every new challenge creates an opportunity for growth.</p><h3>4. Allow Mistakes</h3><p>Children do not build confidence by getting everything right.</p><p>They build confidence by making mistakes and learning that they can recover.</p><p>Mistakes teach resilience, problem-solving, and perseverance.</p><p>Avoid treating mistakes as disasters.</p><p>Treat them as learning opportunities.</p><h3>5. Listen to Their Ideas</h3><p>Children need to feel that their thoughts matter.</p><p>Ask questions.</p><p>Listen carefully.</p><p>Allow them to express themselves.</p><p>A child who feels heard is more likely to develop confidence in their own voice and opinions.</p><h3>6. Avoid Constant Comparisons</h3><p>Every child develops at their own pace.</p><p>Comparing children to siblings, classmates, or friends can create unnecessary self-doubt.</p><p>Instead, focus on helping children become the best version of themselves.</p><p>Their journey is unique.</p><h3>7. Encourage Healthy Independence</h3><p>Confidence grows when children learn to take responsibility.</p><p>Allow them to make choices, complete tasks, and take ownership of certain responsibilities appropriate for their age.</p><p>Independence helps children see themselves as capable and competent.</p><h3>8. Support Their Interests</h3><p>Every child has unique talents and passions.</p><p>Some love books.</p><p>Others love sports.</p><p>Some enjoy music, art, science, or storytelling.</p><p>When parents show interest in what excites their children, they communicate an important message:</p><p>"What matters to you matters to me."</p><h3>9. Model Confidence Yourself</h3><p>Children learn by watching adults.</p><p>If they see parents constantly criticizing themselves, giving up easily, or doubting their abilities, they may adopt similar habits.</p><p>Let children see you trying new things, learning from mistakes, and believing in your own ability to grow.</p><h3>10. Believe in Them Before They Believe in Themselves</h3><p>Sometimes children struggle to see their own potential.</p><p>In those moments, they need adults who can see it for them.</p><p>Your encouragement, support, and belief may be exactly what gives them the courage to keep going.</p><p>Many confident adults can trace their confidence back to someone who believed in them before they believed in themselves.</p><p>Final Thoughts</p><p>Confidence is not built in a day.</p><p>It grows through encouragement, experiences, mistakes, challenges, and supportive relationships.</p><p>Children do not need parents who make life perfect for them.</p><p>They need parents who help them develop the skills and mindset to handle life\'s imperfections.</p><p>When we allow children to try, fail, learn, grow, and try again, we help them build something far more valuable than temporary success.</p><p>We help them build confidence that can last a lifetime.</p><p>B</p><p>How Praise Can Help or Hurt Confidence</p><p>Most parents want their children to feel confident, capable, and valued.</p><p>One of the ways we try to achieve this is through praise.</p><p>We compliment their efforts, celebrate their successes, and encourage</p><p>them when they struggle.</p><p>Praise can be a powerful tool.</p><p>But like many things in parenting, too little can be harmful and too</p><p>much can be harmful too.</p><p>The goal is not simply to praise children.</p><p>The goal is to praise them in ways that build genuine confidence.</p><p>When Praise Helps</p><p>Healthy praise helps children recognize their strengths and efforts.</p><p>It teaches them that hard work matters.</p><p>It encourages them to keep trying when things are difficult.</p><p>For example:</p><p>- "You worked really hard on that project."</p><p>- "I noticed how kind you were to your friend."</p><p>- "You kept practicing even when it was difficult."</p><p>This type of praise focuses on effort, character, perseverance, and</p><p>growth.</p><p>Children who receive this kind of praise often develop confidence based</p><p>on real experiences and accomplishments.</p><p>Their confidence has a foundation.</p><p>When Praise Becomes Praise-Singing</p><p>Sometimes praise stops being encouragement and becomes what some people</p><p>would call praise-singing.</p><p>Children are often smarter than we give them credit for.</p><p>They usually know when they have genuinely achieved something.</p><p>They also know when adults are exaggerating.</p><p>Sometimes a child does something very ordinary and receives a level of</p><p>praise that does not match the accomplishment.</p><p>In those moments, parents may notice a certain look on the child\'s face.</p><p>Almost as if the child is thinking:</p><p>"You\'re doing too much."</p><p>Of course, there are times when enthus</p>','is_featured'=>true,'is_published'=>true,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>'Helping Shy Children Speak Up','description'=>'Gentle ways to encourage quieter children to find their voice.','category'=>'Confidence Building','audience'=>'PARENT','content_type'=>'ARTICLE','body'=>'<p>Shy children have wonderful ideas — they sometimes just need a little encouragement to share them. This article offers gentle, practical strategies.</p>','is_published'=>true,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>'Supporting Reluctant Readers','description'=>'Turn reading resistance into reading joy.','category'=>'Reading & Literacy','audience'=>'PARENT','content_type'=>'ARTICLE','body'=>'<p>Some children need extra encouragement to fall in love with reading. Here are proven ways to help.</p>','is_published'=>true,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>'Public Speaking Skills for Kids','description'=>'Help your child speak with confidence and clarity.','category'=>'Communication & Public Speaking','audience'=>'PARENT','content_type'=>'ARTICLE','body'=>'<p>Public speaking is a skill any child can develop with practice and encouragement.</p>','is_featured'=>true,'is_published'=>true,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>'Encouraging Creativity at Home','description'=>'Simple ways to nurture your child\'s imagination.','category'=>'Creativity & Arts','audience'=>'PARENT','content_type'=>'ARTICLE','body'=>'<p>Creativity is a superpower. Here is how to nurture it every day at home.</p>','is_published'=>true,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>'Signs Your Child Has Leadership Potential','description'=>'Recognising and nurturing young leaders.','category'=>'Leadership & Life Skills','audience'=>'PARENT','content_type'=>'ARTICLE','body'=>'<p>Every child can develop leadership skills. Learn to spot and encourage them.</p>','is_published'=>true,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>'Positive Screen Time','description'=>'Making technology work for your family.','category'=>'Technology & Digital Wellness','audience'=>'PARENT','content_type'=>'ARTICLE','body'=>'<p>Screen time does not have to be a battle. Here is how to make it positive and purposeful.</p>','is_published'=>true,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>'Understanding Child Development','description'=>'What to expect at every age and stage.','category'=>'Child Development','audience'=>'PARENT','content_type'=>'ARTICLE','body'=>'<p>Understanding how children grow helps us support them better at each stage.</p>','is_published'=>true,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>'Raising Globally Minded Children','description'=>'Nurturing curiosity about the wider world.','category'=>'Global Learning & Culture','audience'=>'PARENT','content_type'=>'ARTICLE','body'=>'<p>Help your child appreciate different cultures and become a global citizen.</p>','is_published'=>true,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>'Emotional Intelligence in Children','description'=>'Helping children understand and manage feelings.','category'=>'Emotional Wellbeing','audience'=>'PARENT','content_type'=>'ARTICLE','body'=>'<p>Emotional intelligence is one of the strongest predictors of lifelong wellbeing.</p>','is_published'=>true,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>'How to Raise a Child Who Loves Reading','description'=>'Simple habits that turn reading into a joy, not a chore.','category'=>'Reading & Literacy','audience'=>'PARENT','content_type'=>'ARTICLE','body'=>'<p>A love of reading is one of the greatest gifts you can give...</p>','is_featured'=>true,'is_published'=>true,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>'Helping Children Speak Confidently','description'=>'Build your childs public speaking and communication skills.','category'=>'Communication & Public Speaking','audience'=>'PARENT','content_type'=>'ARTICLE','body'=>'<p>Communication is a skill that can be nurtured...</p>','is_featured'=>false,'is_published'=>true,'created_at'=>now(),'updated_at'=>now()],
        ]);
        $this->command->info('   + Demo library resources added');
    }


    private function seedActivities(): void
    {
        if (\App\Models\Activity::count() > 0) return;
        \App\Models\Activity::insert([
            ['title'=>'Animal Word Search','theme'=>'Animals','age_bracket'=>'3-7','activity_type'=>'WORD_SEARCH','description'=>'Find all the hidden animal names in this fun word search!','is_published'=>true,'sort_order'=>1,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>'Pirate Treasure Maze','theme'=>'Pirates','age_bracket'=>'8-12','activity_type'=>'MAZE','description'=>'Help the pirate find the treasure through the maze.','is_published'=>true,'sort_order'=>2,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>'Grammar Crossword Challenge','theme'=>'Grammar','age_bracket'=>'9-16','activity_type'=>'CROSSWORD','description'=>'Test your grammar knowledge with this crossword.','is_published'=>true,'sort_order'=>3,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>'Money Matching Game','theme'=>'Financial Literacy','age_bracket'=>'8-12','activity_type'=>'MATCHING','description'=>'Match the coins and notes to their values.','is_published'=>true,'sort_order'=>4,'created_at'=>now(),'updated_at'=>now()],
        ]);
        $this->command->info('   + Demo activities added');
    }


    private function seedBooks(): void
    {
        if (\App\Models\Book::count() > 0) return;
        $books = [
            ['title'=>'Things Fall Apart','slug'=>'things-fall-apart','author'=>'Chinua Achebe','country'=>'Nigeria','age_group'=>'13-18','genre'=>'Classic Fiction','category'=>'Nigerian Classics','reading_level'=>'Advanced','reading_time'=>'6 hours','themes'=>'Culture, Tradition, Change','about'=>'A landmark novel about Okonkwo, a respected leader in an Igbo village, and the arrival of colonial change.','what_children_learn'=>'About Igbo culture, history, and the impact of colonialism.','why_recommend'=>'One of the most important African novels ever written.','where_to_find'=>'Available at libraries, bookstores, and major online retailers.','is_featured'=>true,'is_book_of_month'=>true,'is_published'=>true,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>'Sugar Girl','slug'=>'sugar-girl','author'=>'Kola Onadipe','country'=>'Nigeria','age_group'=>'6-8','genre'=>'Folktale','category'=>'Nigerian Classics','reading_level'=>'Beginner','reading_time'=>'30 mins','themes'=>'Courage, Adventure','about'=>'A beloved Nigerian childrens tale about a brave little girl.','what_children_learn'=>'Courage and kindness through a classic story.','why_recommend'=>'A gentle introduction to Nigerian storytelling.','where_to_find'=>'Available at Nigerian bookstores and libraries.','is_featured'=>true,'is_published'=>true,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>'The Lion and the Jewel','slug'=>'the-lion-and-the-jewel','author'=>'Wole Soyinka','country'=>'Nigeria','age_group'=>'13-18','genre'=>'Drama','category'=>'African Classics','reading_level'=>'Advanced','reading_time'=>'3 hours','themes'=>'Tradition, Modernity','about'=>'A witty play exploring tradition and modernity in a Yoruba village.','what_children_learn'=>'About drama, wit, and cultural values.','why_recommend'=>'A brilliant introduction to African theatre by a Nobel laureate.','where_to_find'=>'Available at libraries and bookstores worldwide.','is_featured'=>true,'is_published'=>true,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>'Anansi the Spider','slug'=>'anansi-the-spider','author'=>'Traditional (Ashanti)','country'=>'Ghana','age_group'=>'3-5','genre'=>'Folktale','category'=>'African Classics','reading_level'=>'Beginner','reading_time'=>'15 mins','themes'=>'Wisdom, Cleverness','about'=>'Classic West African folktales about the clever spider Anansi.','what_children_learn'=>'Wisdom and cleverness through traditional folktales.','why_recommend'=>'Timeless tales that delight young children.','where_to_find'=>'Available in many picture-book editions at libraries.','is_published'=>true,'created_at'=>now(),'updated_at'=>now()],
        ];
        \App\Models\Book::insert($books);
        $this->command->info('   + Demo books added');
    }

}
