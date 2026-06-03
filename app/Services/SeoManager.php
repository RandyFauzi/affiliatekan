<?php

namespace App\Services;

class SeoManager
{
    protected string $title = 'Platform Afiliasi B2B Terbaik';
    protected string $description = 'Platform pelacak kemitraan afiliasi otomatis untuk bisnis SaaS B2B. Lacak klik referral secara presisi dan cairkan komisi secara instan.';
    protected array $keywords = ['affiliate', 'b2b affiliate', 'saas tracking', 'tracking referral', 'affiliatekan'];
    protected string $canonical = '';
    protected string $robots = 'index, follow';
    protected array $ogTags = [];
    protected array $twitterTags = [];
    protected array $schemas = [];

    public function __construct()
    {
        $this->canonical = url()->current();
        
        // Initialize default OG and Twitter tags
        $this->setOg('title', 'Affiliatekan - Platform Afiliasi B2B Terbaik');
        $this->setOg('description', $this->description);
        $this->setOg('url', $this->canonical);
        $this->setOg('type', 'website');
        $this->setOg('image', asset('images/Logo - Affilaitekan.svg'));

        $this->setTwitter('card', 'summary_large_image');
        $this->setTwitter('title', 'Affiliatekan - Platform Afiliasi B2B Terbaik');
        $this->setTwitter('description', $this->description);
        $this->setTwitter('image', asset('images/Logo - Affilaitekan.svg'));
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        $this->setOg('title', $title . ' | Affiliatekan - Platform Afiliasi B2B Terbaik');
        $this->setTwitter('title', $title . ' | Affiliatekan - Platform Afiliasi B2B Terbaik');
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title . ' | Affiliatekan - Platform Afiliasi B2B Terbaik';
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        $this->setOg('description', $description);
        $this->setTwitter('description', $description);
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setKeywords(array $keywords): self
    {
        $this->keywords = $keywords;
        return $this;
    }

    public function getKeywords(): string
    {
        return implode(', ', $this->keywords);
    }

    public function setCanonical(string $url): self
    {
        $this->canonical = $url;
        $this->setOg('url', $url);
        return $this;
    }

    public function getCanonical(): string
    {
        return $this->canonical ?: url()->current();
    }

    public function setRobots(string $robots): self
    {
        $this->robots = $robots;
        return $this;
    }

    public function getRobots(): string
    {
        return $this->robots;
    }

    public function setOg(string $property, string $content): self
    {
        $this->ogTags[$property] = $content;
        return $this;
    }

    public function getOgTags(): array
    {
        return $this->ogTags;
    }

    public function setTwitter(string $name, string $content): self
    {
        $this->twitterTags[$name] = $content;
        return $this;
    }

    public function getTwitterTags(): array
    {
        return $this->twitterTags;
    }

    public function addSchema(array $schema): self
    {
        $this->schemas[] = $schema;
        return $this;
    }

    public function renderSchemas(): string
    {
        $html = '';
        foreach ($this->schemas as $schema) {
            $html .= '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>' . "\n";
        }
        return $html;
    }
}
