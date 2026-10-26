<?php

namespace Database\Seeders;

use App\Models\Technology;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TechnologySeeder extends Seeder
{
    public function run(): void
    {
        $technologies = [
            [
                'name' => 'PHP',
                'category' => 'Programming Language',
                'description' => 'A general-purpose programming language widely used for web development.',
                'icon' => 'fa-brands fa-php',
                'color' => '#777BB4',
            ],
            [
                'name' => 'JavaScript',
                'category' => 'Programming Language',
                'description' => 'A programming language commonly used to create interactive web applications.',
                'icon' => 'fa-brands fa-js',
                'color' => '#F7DF1E',
            ],
            [
                'name' => 'Python',
                'category' => 'Programming Language',
                'description' => 'A high-level programming language used for web development, automation, data science, and AI.',
                'icon' => 'fa-brands fa-python',
                'color' => '#3776AB',
            ],
            [
                'name' => 'Java',
                'category' => 'Programming Language',
                'description' => 'A general-purpose, object-oriented programming language widely used for enterprise applications.',
                'icon' => 'fa-brands fa-java',
                'color' => '#ED8B00',
            ],
            [
                'name' => 'C#',
                'category' => 'Programming Language',
                'description' => 'A modern object-oriented programming language developed by Microsoft.',
                'icon' => 'fa-solid fa-code',
                'color' => '#512BD4',
            ],
            [
                'name' => 'Laravel',
                'category' => 'Framework',
                'description' => 'A PHP framework for building modern web applications.',
                'icon' => 'fa-brands fa-laravel',
                'color' => '#FF2D20',
            ],
            [
                'name' => 'Vue.js',
                'category' => 'Framework',
                'description' => 'A progressive JavaScript framework for building user interfaces.',
                'icon' => 'fa-brands fa-vuejs',
                'color' => '#42B883',
            ],
            [
                'name' => 'React',
                'category' => 'Framework',
                'description' => 'A JavaScript library for building user interfaces and interactive web applications.',
                'icon' => 'fa-brands fa-react',
                'color' => '#61DAFB',
            ],
            [
                'name' => 'Angular',
                'category' => 'Framework',
                'description' => 'A TypeScript-based web application framework developed by Google.',
                'icon' => 'fa-brands fa-angular',
                'color' => '#DD0031',
            ],
            [
                'name' => 'Node.js',
                'category' => 'Runtime',
                'description' => 'A JavaScript runtime used to build scalable server-side applications.',
                'icon' => 'fa-brands fa-node-js',
                'color' => '#339933',
            ],
            [
                'name' => 'MySQL',
                'category' => 'Database',
                'description' => 'An open-source relational database management system.',
                'icon' => 'fa-solid fa-database',
                'color' => '#4479A1',
            ],
            [
                'name' => 'PostgreSQL',
                'category' => 'Database',
                'description' => 'An open-source object-relational database system known for reliability and advanced features.',
                'icon' => 'fa-solid fa-database',
                'color' => '#4169E1',
            ],
            [
                'name' => 'MongoDB',
                'category' => 'Database',
                'description' => 'A document-oriented NoSQL database designed for flexible and scalable applications.',
                'icon' => 'fa-solid fa-database',
                'color' => '#47A248',
            ],
            [
                'name' => 'Redis',
                'category' => 'Database / Cache',
                'description' => 'An in-memory data store commonly used for caching, sessions, queues, and real-time applications.',
                'icon' => 'fa-solid fa-database',
                'color' => '#DC382D',
            ],
            [
                'name' => 'HTML',
                'category' => 'Markup Language',
                'description' => 'The standard markup language used to structure content on the web.',
                'icon' => 'fa-brands fa-html5',
                'color' => '#E34F26',
            ],
            [
                'name' => 'CSS',
                'category' => 'Styling',
                'description' => 'A stylesheet language used to define the presentation and layout of web pages.',
                'icon' => 'fa-brands fa-css3-alt',
                'color' => '#1572B6',
            ],
            [
                'name' => 'Tailwind CSS',
                'category' => 'CSS Framework',
                'description' => 'A utility-first CSS framework for rapidly building custom user interfaces.',
                'icon' => 'fa-solid fa-wind',
                'color' => '#06B6D4',
            ],
            [
                'name' => 'Bootstrap',
                'category' => 'CSS Framework',
                'description' => 'A popular front-end framework for developing responsive websites and applications.',
                'icon' => 'fa-brands fa-bootstrap',
                'color' => '#7952B3',
            ],
            [
                'name' => 'DaisyUI',
                'category' => 'UI Component Library',
                'description' => 'A component library built on top of Tailwind CSS.',
                'icon' => 'fa-solid fa-layer-group',
                'color' => '#1AD1A5',
            ],
            [
                'name' => 'Docker',
                'category' => 'DevOps',
                'description' => 'A platform for developing, shipping, and running applications in containers.',
                'icon' => 'fa-brands fa-docker',
                'color' => '#2496ED',
            ],
            [
                'name' => 'Kubernetes',
                'category' => 'DevOps',
                'description' => 'An open-source platform for automating deployment, scaling, and management of containers.',
                'icon' => 'fa-solid fa-dharmachakra',
                'color' => '#326CE5',
            ],
            [
                'name' => 'AWS',
                'category' => 'Cloud Platform',
                'description' => 'A cloud computing platform providing infrastructure and managed services.',
                'icon' => 'fa-brands fa-aws',
                'color' => '#FF9900',
            ],
            [
                'name' => 'Microsoft Azure',
                'category' => 'Cloud Platform',
                'description' => 'Microsoft cloud computing platform providing infrastructure, application, and data services.',
                'icon' => 'fa-brands fa-microsoft',
                'color' => '#0078D4',
            ],
            [
                'name' => 'Google Cloud',
                'category' => 'Cloud Platform',
                'description' => 'Google cloud computing platform providing infrastructure and managed services.',
                'icon' => 'fa-brands fa-google',
                'color' => '#4285F4',
            ],
            [
                'name' => 'Git',
                'category' => 'Version Control',
                'description' => 'A distributed version control system for tracking changes in source code.',
                'icon' => 'fa-brands fa-git-alt',
                'color' => '#F05032',
            ],
            [
                'name' => 'GitHub',
                'category' => 'Development Platform',
                'description' => 'A development platform for hosting Git repositories and collaborating on software projects.',
                'icon' => 'fa-brands fa-github',
                'color' => '#181717',
            ],
            [
                'name' => 'WordPress',
                'category' => 'CMS',
                'description' => 'An open-source content management system used to build websites and web applications.',
                'icon' => 'fa-brands fa-wordpress',
                'color' => '#21759B',
            ],
            [
                'name' => 'Figma',
                'category' => 'Design Tool',
                'description' => 'A collaborative design and prototyping tool for creating digital interfaces.',
                'icon' => 'fa-brands fa-figma',
                'color' => '#F24E1E',
            ],
            [
                'name' => '.NET',
                'category' => 'Development Platform',
                'description' => 'A cross-platform development platform from Microsoft for building modern applications.',
                'icon' => 'fa-solid fa-code',
                'color' => '#512BD4',
            ],
            [
                'name' => 'Flutter',
                'category' => 'Framework',
                'description' => 'A UI toolkit for building applications for mobile, web, and desktop platforms.',
                'icon' => 'fa-solid fa-mobile-screen',
                'color' => '#02569B',
            ],
            [
                'name' => 'Django',
                'category' => 'Framework',
                'description' => 'A high-level Python web framework focused on rapid and maintainable development.',
                'icon' => 'fa-brands fa-python',
                'color' => '#092E20',
            ],
            [
                'name' => 'Spring Boot',
                'category' => 'Framework',
                'description' => 'A Java framework for building production-ready applications with the Spring ecosystem.',
                'icon' => 'fa-solid fa-leaf',
                'color' => '#6DB33F',
            ],
            [
                'name' => 'Vite',
                'category' => 'Build Tool',
                'description' => 'A modern frontend build tool providing fast development and optimized production builds.',
                'icon' => 'fa-solid fa-bolt',
                'color' => '#646CFF',
            ],
            [
                'name' => 'Linux',
                'category' => 'Operating System',
                'description' => 'An open-source operating system widely used for servers, development, and infrastructure.',
                'icon' => 'fa-brands fa-linux',
                'color' => '#FCC624',
            ],
            [
                'name' => 'Postman',
                'category' => 'API Development Tool',
                'description' => 'A platform for developing, testing, documenting, and working with APIs.',
                'icon' => 'fa-solid fa-paper-plane',
                'color' => '#FF6C37',
            ],
        ];

        foreach ($technologies as $index => $technology) {
            Technology::withTrashed()->updateOrCreate(
                ['slug' => Str::slug($technology['name'])],
                [
                    'name' => $technology['name'],
                    'category' => $technology['category'],
                    'description' => $technology['description'],
                    'icon' => $technology['icon'],
                    'color' => $technology['color'],
                    'is_active' => true,
                    'sort_order' => $index + 1,
                    'deleted_at' => null,
                    'deleted_by' => null,
                ]
            );
        }
    }
}