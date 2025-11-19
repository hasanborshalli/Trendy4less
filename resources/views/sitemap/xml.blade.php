<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ url('/') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ route("shop.index") }}</loc>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc>{{ route("about") }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>

    @foreach($categories as $category)
    <url>
        <loc>{{ route("shop.index", ["category" => $category->slug]) }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach

    @foreach($products as $product)
    <url>
        <loc>{{ route("shop.show", $product->slug) }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach
</urlset>