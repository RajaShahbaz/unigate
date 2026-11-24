<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display the blog detail page
     */
    public function show($id)
    {
        // For now, we'll use a mock blog object
        // In production, you would fetch from database:
        // $blog = Blog::with('author', 'tags')->findOrFail($id);
        
        $blog = (object) [
            'id' => $id,
            'title' => '77+ Smart Contract Use Cases Enabled By Chainlink',
            'content' => $this->getSampleContent(),
            'featured_image' => null,
            'published_at' => '2019-05-17',
            'updated_at' => '2025-05-02',
            'read_time' => 94,
            'author' => (object) [
                'id' => 1,
                'name' => 'Michelle Tolan',
                'avatar' => null,
                'bio' => 'A business, technology, and executive summit focused on big ideas and strategies for building corporations.',
            ],
            'tags' => ['DATA FEEDS', 'DEFI', 'EDUCATION', 'NFTS'],
        ];

        return view('blog.blog-detail', compact('blog'));
    }

    /**
     * Sample content for demonstration
     */
    private function getSampleContent()
    {
        return '
        <h2>Understanding Smart Contracts</h2>
        <p>Smart contracts are self-executing contracts with the terms of the agreement directly written into code. They run on blockchain networks and automatically execute when predetermined conditions are met.</p>
        
        <h2>The Blockchain Oracle Problem</h2>
        <p>One of the key limitations of smart contracts is their inability to interact with external data sources. This is known as the "blockchain oracle problem." Smart contracts operate in isolation and cannot access real-world data, which limits their functionality.</p>
        
        <h2>Solution: Hybrid Smart Contracts</h2>
        <p>Hybrid smart contracts combine on-chain code with off-chain infrastructure to create more powerful applications. Oracles act as middleware that can retrieve external data, push outputs, and perform off-chain computations, creating a secure two-way bridge between blockchains and the real world.</p>
        
        <div class="article-diagram">
            <h3>The Chainlink Network connects smart contracts to offchain data and events</h3>
            <div class="diagram-columns">
                <div class="diagram-column">
                    <h4>Any Input</h4>
                    <div class="diagram-icons">
                        <div class="diagram-icon"><i class="fas fa-cloud"></i></div>
                        <div class="diagram-icon"><i class="fas fa-database"></i></div>
                        <div class="diagram-icon"><i class="fas fa-mobile-alt"></i></div>
                        <div class="diagram-icon"><i class="fas fa-wifi"></i></div>
                        <div class="diagram-icon"><i class="fas fa-dollar-sign"></i></div>
                        <div class="diagram-icon"><i class="fas fa-calendar"></i></div>
                    </div>
                </div>
                <div class="diagram-arrow"><i class="fas fa-arrow-right"></i></div>
                <div class="diagram-column">
                    <h4>Any Blockchain</h4>
                    <div class="diagram-blockchain">
                        <i class="fas fa-link" style="color: white; font-size: 48px;"></i>
                    </div>
                </div>
                <div class="diagram-arrow"><i class="fas fa-arrow-right"></i></div>
                <div class="diagram-column">
                    <h4>Any Output</h4>
                    <div class="diagram-icons">
                        <div class="diagram-icon"><i class="fas fa-dollar-sign"></i></div>
                        <div class="diagram-icon"><i class="fas fa-file-alt"></i></div>
                        <div class="diagram-icon"><i class="fas fa-database"></i></div>
                        <div class="diagram-icon"><i class="fas fa-mobile-alt"></i></div>
                        <div class="diagram-icon"><i class="fas fa-wifi"></i></div>
                    </div>
                </div>
            </div>
        </div>
        
        <h2>Chainlink Services</h2>
        <p>Chainlink is a widely used decentralized oracle network that secures billions of dollars in value. It provides several key services:</p>
        <ul>
            <li><strong>Data Feeds:</strong> Real-time price feeds and market data</li>
            <li><strong>Data Streams:</strong> High-frequency data updates</li>
            <li><strong>Cross-Chain Interoperability Protocol (CCIP):</strong> Secure cross-chain communication</li>
            <li><strong>Proof of Reserve:</strong> Cryptographic verification of reserves</li>
            <li><strong>Functions:</strong> Serverless compute for smart contracts</li>
            <li><strong>Automation:</strong> Automated contract execution</li>
            <li><strong>Verifiable Random Functions (VRF):</strong> Provably fair randomness</li>
        </ul>
        
        <h2>Developer Tools</h2>
        <p>Chainlink provides comprehensive developer tools and documentation to help you build with the network. Explore the 77+ ways to use the Chainlink Network, find us on Discord or Github, and check out our documentation to get started.</p>
        ';
    }
}
