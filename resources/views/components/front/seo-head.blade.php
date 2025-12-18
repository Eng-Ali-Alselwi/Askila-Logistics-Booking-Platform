@props([
    'title' => 'مجموعة الأسكلة',
    'description' => 'مجموعة الأسكلة للشحن والسفر - خدمات شحن وحجز تذاكر طيران من الباب للباب',
    'keywords' => 'شحن, تذاكر طيران, سفر, السعودية, السودان, الأسكلة',
    'ogImage' => null,
    'canonical' => null,
    'orgSchema' => false,
    'webSiteSchema' => false
])

<title>{{ $title }}</title>
<meta name="description" content="{{ $description }}">
<meta name="keywords" content="{{ $keywords }}">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="website">
<meta property="og:url" content="{{ $canonical ?? request()->url() }}">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
@if($ogImage)
<meta property="og:image" content="{{ $ogImage }}">
@endif

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="{{ $canonical ?? request()->url() }}">
<meta property="twitter:title" content="{{ $title }}">
<meta property="twitter:description" content="{{ $description }}">
@if($ogImage)
<meta property="twitter:image" content="{{ $ogImage }}">
@endif

@if($canonical)
<link rel="canonical" href="{{ $canonical }}">
@endif

@if($orgSchema)
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "Organization",
  "name": "مجموعة الأسكلة",
  "url": "{{ config('app.url') }}",
  "logo": "{{ asset('assets/images/logo/dark.png') }}",
  "description": "مجموعة الأسكلة للشحن والسفر - خدمات شحن وحجز تذاكر طيران من الباب للباب",
  "address": {
    "@@type": "PostalAddress",
    "addressCountry": "SA",
    "addressLocality": "الرياض"
  },
  "contactPoint": {
    "@@type": "ContactPoint",
    "telephone": "+966-11-123-4567",
    "contactType": "customer service"
  },
  "sameAs": [
    "https://www.facebook.com/askila",
    "https://www.twitter.com/askila",
    "https://www.instagram.com/askila"
  ]
}
</script>
@endif

@if($webSiteSchema)
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "WebSite",
  "name": "مجموعة الأسكلة",
  "url": "{{ config('app.url') }}",
  "description": "{{ $description }}",
  "potentialAction": {
    "@@type": "SearchAction",
    "target": "{{ route('home') }}?search={search_term_string}",
    "query-input": "required name=search_term_string"
  }
}
</script>
@endif