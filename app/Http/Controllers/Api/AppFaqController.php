<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\AppFaq\AppFaqInterface;
use Illuminate\Http\Request;

class AppFaqController extends Controller
{
    private AppFaqInterface $appFaq;

    public function __construct(AppFaqInterface $appFaq)
    {
        $this->appFaq = $appFaq;
    }

    /**
     * Get all active FAQs for the app
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $faqs = $this->appFaq->getActiveFaqs();

            return response()->json([
                'error' => false,
                'message' => 'FAQs retrieved successfully',
                'data' => $faqs
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to retrieve FAQs',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a single FAQ by ID
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $faq = $this->appFaq->findById($id);

            if (!$faq) {
                return response()->json([
                    'error' => true,
                    'message' => 'FAQ not found',
                    'data' => null
                ], 404);
            }

            return response()->json([
                'error' => false,
                'message' => 'FAQ retrieved successfully',
                'data' => $faq
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to retrieve FAQ',
                'data' => $e->getMessage()
            ], 500);
        }
    }
}

