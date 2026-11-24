@extends('layouts.home_page.master')

@section('title', ($blog->title ?? 'Blog Post') . ' - UniGate')

@section('css')
<style>
    .blog-detail-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* Header Image Section */
    .blog-header-image {
        width: 100%;
        height: 500px;
        object-fit: cover;
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        position: relative;
        overflow: hidden;
    }

    .blog-header-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Article Header Section */
    .article-header {
        margin-top: 40px;
        margin-bottom: 30px;
    }

    .article-meta {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .author-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #f0f0f0;
    }

    .article-title {
        font-size: 48px;
        font-weight: 700;
        line-height: 1.2;
        color: #1a1a1a;
        margin-bottom: 15px;
    }

    .article-dates {
        color: #666;
        font-size: 14px;
        margin-bottom: 10px;
    }

    .read-time {
        color: #666;
        font-size: 14px;
    }

    /* Social Sharing Buttons */
    .social-sharing {
        display: flex;
        gap: 10px;
        margin: 30px 0;
        flex-wrap: wrap;
    }

    .social-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 45px;
        height: 45px;
        border-radius: 8px;
        color: white;
        text-decoration: none;
        transition: transform 0.2s, box-shadow 0.2s;
        font-size: 20px;
    }

    .social-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        color: white;
    }

    .social-btn.facebook { background-color: #1877f2; }
    .social-btn.twitter { background-color: #1da1f2; }
    .social-btn.reddit { background-color: #ff4500; }
    .social-btn.linkedin { background-color: #0077b5; }
    .social-btn.whatsapp { background-color: #25d366; }
    .social-btn.email { background-color: #ff6b35; }

    /* Utility Bar */
    .utility-bar {
        background: #f5f5f5;
        padding: 15px 20px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 15px;
        margin: 30px 0;
        flex-wrap: wrap;
    }

    .utility-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 15px;
        background: white;
        border: 1px solid #ddd;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 14px;
        color: #333;
    }

    .utility-btn:hover {
        background: #f0f0f0;
        border-color: #bbb;
    }

    .utility-search {
        flex: 1;
        min-width: 200px;
        position: relative;
    }

    .utility-search input {
        width: 100%;
        padding: 8px 15px 8px 40px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
    }

    .utility-search i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #999;
    }

    .utility-category {
        padding: 8px 15px;
        border: 1px solid #ddd;
        border-radius: 6px;
        background: white;
        font-size: 14px;
        cursor: pointer;
    }

    /* Article Content */
    .article-content {
        font-size: 18px;
        line-height: 1.8;
        color: #333;
        margin: 40px 0;
    }

    .article-content h2 {
        font-size: 32px;
        font-weight: 700;
        margin: 40px 0 20px;
        color: #1a1a1a;
    }

    .article-content h3 {
        font-size: 24px;
        font-weight: 600;
        margin: 30px 0 15px;
        color: #1a1a1a;
    }

    .article-content p {
        margin-bottom: 20px;
    }

    .article-content ul, .article-content ol {
        margin: 20px 0;
        padding-left: 30px;
    }

    .article-content li {
        margin-bottom: 10px;
    }

    .article-content img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 30px 0;
    }

    .article-diagram {
        background: #f8f9fa;
        padding: 40px;
        border-radius: 12px;
        margin: 40px 0;
        text-align: center;
    }

    .article-diagram h3 {
        margin-bottom: 30px;
        color: #1a1a1a;
    }

    .diagram-columns {
        display: flex;
        justify-content: space-around;
        align-items: center;
        gap: 30px;
        flex-wrap: wrap;
    }

    .diagram-column {
        flex: 1;
        min-width: 200px;
    }

    .diagram-column h4 {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 20px;
        color: #1a1a1a;
    }

    .diagram-icons {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        justify-content: center;
    }

    .diagram-icon {
        width: 50px;
        height: 50px;
        background: white;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: rgba(70, 90, 40, 1);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .diagram-blockchain {
        width: 120px;
        height: 120px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .diagram-blockchain::before,
    .diagram-blockchain::after {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        border: 2px solid rgba(255,255,255,0.3);
        border-radius: 12px;
    }

    .diagram-blockchain::before {
        transform: scale(0.85);
    }

    .diagram-blockchain::after {
        transform: scale(0.7);
    }

    .diagram-arrow {
        font-size: 32px;
        color: rgba(70, 90, 40, 1);
    }

    /* Tags Section */
    .article-tags {
        margin: 50px 0 30px;
        padding-top: 30px;
        border-top: 1px solid #e0e0e0;
    }

    .tags-title {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 15px;
        color: #1a1a1a;
    }

    .tags-list {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .tag {
        display: inline-block;
        padding: 8px 16px;
        background: #f0f0f0;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        color: #333;
        text-decoration: none;
        transition: all 0.2s;
    }

    .tag:hover {
        background: rgba(70, 90, 40, 1);
        color: white;
        text-decoration: none;
    }

    /* Author Biography */
    .author-bio {
        background: #fff9e6;
        padding: 40px;
        border-radius: 12px;
        margin: 50px 0;
        display: flex;
        gap: 30px;
        flex-wrap: wrap;
    }

    .author-bio-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid white;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .author-bio-content {
        flex: 1;
        min-width: 300px;
    }

    .author-bio-name {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 15px;
        color: #1a1a1a;
    }

    .author-bio-social {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
    }

    .author-bio-social a {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
        color: #333;
        text-decoration: none;
        transition: all 0.2s;
        font-size: 18px;
    }

    .author-bio-social a:hover {
        background: rgba(70, 90, 40, 1);
        color: white;
        transform: translateY(-2px);
    }

    .author-bio-text {
        font-size: 16px;
        line-height: 1.6;
        color: #555;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .article-title {
            font-size: 32px;
        }

        .blog-header-image {
            height: 300px;
        }

        .diagram-columns {
            flex-direction: column;
        }

        .diagram-arrow {
            transform: rotate(90deg);
        }

        .author-bio {
            flex-direction: column;
            text-align: center;
        }

        .utility-bar {
            flex-direction: column;
        }

        .utility-search {
            width: 100%;
        }
    }
</style>
@endsection

@section('content')
<div class="blog-detail-container">
    <!-- Header Image -->
    <div class="blog-header-image">
        @if(isset($blog) && $blog->featured_image)
            <img src="{{ asset('storage/' . $blog->featured_image) }}" alt="{{ $blog->title ?? 'Blog Post' }}">
        @else
            <div style="display: flex; align-items: center; justify-content: center; height: 100%; color: white; font-size: 24px;">
                <i class="fas fa-image"></i>
            </div>
        @endif
    </div>

    <!-- Article Header -->
    <div class="article-header">
        <div class="article-meta">
            @if(isset($blog) && $blog->author && $blog->author->avatar)
                <img src="{{ asset('storage/' . $blog->author->avatar) }}" alt="{{ $blog->author->name ?? 'Author' }}" class="author-avatar">
            @elseif(isset($blog) && $blog->author)
                <div class="author-avatar" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 24px; font-weight: bold;">
                    {{ strtoupper(substr($blog->author->name ?? 'A', 0, 1)) }}
                </div>
            @else
                <div class="author-avatar" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 24px; font-weight: bold;">
                    A
                </div>
            @endif
            <div>
                <div class="article-title">{{ $blog->title ?? 'Blog Post Title' }}</div>
                <div class="article-dates">
                    Published {{ isset($blog) && $blog->published_at ? \Carbon\Carbon::parse($blog->published_at)->format('M d, Y') : date('M d, Y') }} 
                    @if(isset($blog) && $blog->updated_at && $blog->updated_at != $blog->published_at)
                        | Updated {{ \Carbon\Carbon::parse($blog->updated_at)->format('M d, Y') }}
                    @endif
                </div>
                <div class="read-time">{{ $blog->read_time ?? '5' }} min read</div>
            </div>
        </div>
    </div>

    <!-- Social Sharing Buttons -->
    <div class="social-sharing">
        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" target="_blank" class="social-btn facebook" title="Share on Facebook">
            <i class="fab fa-facebook-f"></i>
        </a>
        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($blog->title ?? '') }}" target="_blank" class="social-btn twitter" title="Share on Twitter">
            <i class="fab fa-twitter"></i>
        </a>
        <a href="https://www.reddit.com/submit?url={{ urlencode(request()->fullUrl()) }}&title={{ urlencode($blog->title ?? '') }}" target="_blank" class="social-btn reddit" title="Share on Reddit">
            <i class="fab fa-reddit-alien"></i>
        </a>
        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->fullUrl()) }}" target="_blank" class="social-btn linkedin" title="Share on LinkedIn">
            <i class="fab fa-linkedin-in"></i>
        </a>
        <a href="https://wa.me/?text={{ urlencode(($blog->title ?? '') . ' ' . request()->fullUrl()) }}" target="_blank" class="social-btn whatsapp" title="Share on WhatsApp">
            <i class="fab fa-whatsapp"></i>
        </a>
        <a href="mailto:?subject={{ urlencode($blog->title ?? '') }}&body={{ urlencode(request()->fullUrl()) }}" class="social-btn email" title="Share via Email">
            <i class="fas fa-envelope"></i>
        </a>
    </div>

    <!-- Utility Bar -->
    <div class="utility-bar">
        <button class="utility-btn" id="listenBtn" onclick="toggleListen()">
            <i class="fas fa-volume-up"></i>
            <span>Listen</span>
        </button>
        <button class="utility-btn" id="pauseBtn" onclick="togglePause()" style="display: none;">
            <i class="fas fa-pause"></i>
            <span>Pause</span>
        </button>
        <div class="utility-search">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search blog" id="blogSearch">
        </div>
        <select class="utility-category" id="categorySelect">
            <option value="">Category</option>
            <option value="all">All Categories</option>
            <option value="education">Education</option>
            <option value="technology">Technology</option>
            <option value="news">News</option>
        </select>
        <button class="utility-btn" onclick="window.print()">
            <i class="fas fa-share-square"></i>
        </button>
    </div>

    <!-- Article Content -->
    <div class="article-content">
        {!! $blog->content ?? '<p>Blog content goes here...</p>' !!}
    </div>

    <!-- Tags Section -->
    @if(isset($blog) && $blog->tags && count($blog->tags) > 0)
    <div class="article-tags">
        <div class="tags-title">Tags:</div>
        <div class="tags-list">
            @foreach($blog->tags as $tag)
                <a href="#" class="tag">{{ strtoupper($tag) }}</a>
            @endforeach
        </div>
    </div>
    @else
    <div class="article-tags">
        <div class="tags-title">Tags:</div>
        <div class="tags-list">
            <a href="#" class="tag">DATA FEEDS</a>
            <a href="#" class="tag">DEFI</a>
            <a href="#" class="tag">EDUCATION</a>
            <a href="#" class="tag">NFTS</a>
        </div>
    </div>
    @endif

    <!-- Author Biography -->
    <div class="author-bio">
        @if(isset($blog) && $blog->author && $blog->author->avatar)
            <img src="{{ asset('storage/' . $blog->author->avatar) }}" alt="{{ $blog->author->name ?? 'Author' }}" class="author-bio-avatar">
        @elseif(isset($blog) && $blog->author)
            <div class="author-bio-avatar" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 36px; font-weight: bold;">
                {{ strtoupper(substr($blog->author->name ?? 'A', 0, 1)) }}
            </div>
        @else
            <div class="author-bio-avatar" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 36px; font-weight: bold;">
                A
            </div>
        @endif
        <div class="author-bio-content">
            <div class="author-bio-name">{{ $blog->author->name ?? 'Author Name' }}</div>
            <div class="author-bio-social">
                <a href="#" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="#" title="YouTube"><i class="fab fa-youtube"></i></a>
                <a href="#" title="Email"><i class="fas fa-envelope"></i></a>
            </div>
            <div class="author-bio-text">
                {{ $blog->author->bio ?? 'This is a sample author biography. The author is a business, technology, and executive summit focused on big ideas and strategies for building corporations.' }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    function toggleListen() {
        document.getElementById('listenBtn').style.display = 'none';
        document.getElementById('pauseBtn').style.display = 'inline-flex';
        // Add text-to-speech functionality here if needed
        console.log('Listen started');
    }

    function togglePause() {
        document.getElementById('listenBtn').style.display = 'inline-flex';
        document.getElementById('pauseBtn').style.display = 'none';
        console.log('Paused');
    }

    // Search functionality
    document.getElementById('blogSearch').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            const searchTerm = this.value;
            if (searchTerm.trim()) {
                // Implement search functionality
                console.log('Searching for:', searchTerm);
                // You can redirect to search page or filter content
            }
        }
    });

    // Category filter
    document.getElementById('categorySelect').addEventListener('change', function() {
        const category = this.value;
        if (category) {
            // Implement category filtering
            console.log('Filtering by category:', category);
        }
    });
</script>
@endsection
