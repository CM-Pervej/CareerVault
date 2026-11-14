<?php
namespace Database\Seeders;
use App\Models\TechCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
class TechCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Programming Language', 'description' => 'Programming languages used to develop software, applications, and systems.'],
            ['name' => 'Framework', 'description' => 'Frameworks and libraries used to build applications and software solutions.'],
            ['name' => 'Runtime', 'description' => 'Runtime environments used to execute applications and code.'],
            ['name' => 'Database', 'description' => 'Database management systems used to store, organize, and manage application data.'],
            ['name' => 'Database / Cache', 'description' => 'Data storage and caching technologies used to improve application performance.'],
            ['name' => 'Markup Language', 'description' => 'Markup languages used to structure and organize digital content.'],
            ['name' => 'Styling', 'description' => 'Technologies used to style and design user interfaces and web pages.'],
            ['name' => 'CSS Framework', 'description' => 'CSS frameworks used to build responsive and consistent user interfaces.'],
            ['name' => 'UI Component Library', 'description' => 'Reusable user interface components and design systems.'],
            ['name' => 'DevOps', 'description' => 'Tools and technologies used for development operations, automation, and deployment.'],
            ['name' => 'Cloud Platform', 'description' => 'Cloud computing platforms providing infrastructure and managed services.'],
            ['name' => 'Version Control', 'description' => 'Tools used to track, manage, and collaborate on source code changes.'],
            ['name' => 'Development Platform', 'description' => 'Platforms and ecosystems used to develop, host, and manage applications.'],
            ['name' => 'CMS', 'description' => 'Content management systems used to create and manage digital content.'],
            ['name' => 'Design Tool', 'description' => 'Tools used for interface design, prototyping, and visual collaboration.'],
            ['name' => 'Build Tool', 'description' => 'Tools used to compile, bundle, and optimize application assets.'],
            ['name' => 'Operating System', 'description' => 'Operating systems used for application development, servers, and infrastructure.'],
            ['name' => 'API Development Tool', 'description' => 'Tools used to develop, test, document, and manage APIs.'],
        ];
        foreach ($categories as $index => $category) {
            TechCategory::withTrashed()->updateOrCreate(
                ['slug' => Str::slug($category['name'])],
                [
                    'name' => $category['name'],
                    'description' => $category['description'],
                    'is_active' => true,
                    'sort_order' => $index + 1,
                    'deleted_at' => null,
                    'deleted_by' => null,
                ]
            );
        }
    }
}