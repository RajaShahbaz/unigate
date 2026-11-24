<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\AppFaq\AppFaqInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AppFaqAdminController extends Controller
{
    private AppFaqInterface $appFaq;

    public function __construct(AppFaqInterface $appFaq)
    {
        $this->appFaq = $appFaq;
    }

    /**
     * Get all FAQs (for admin)
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 15);
            $search = $request->get('search');
            $status = $request->get('status');

            $query = $this->appFaq->builder();

            // Search
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('question', 'like', '%' . $search . '%')
                      ->orWhere('answer', 'like', '%' . $search . '%');
                });
            }

            // Filter by status
            if ($status !== null && $status !== '') {
                $query->where('status', $status);
            }

            // Order by order field
            $query->ordered();

            $faqs = $query->paginate($perPage);

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
     * Store a new FAQ
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'question' => 'required|string|max:255',
                'answer' => 'required|string',
                'order' => 'nullable|integer|min:0',
                'status' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Validation failed',
                    'data' => $validator->errors()
                ], 422);
            }

            $faq = $this->appFaq->create($request->all());

            return response()->json([
                'error' => false,
                'message' => 'FAQ created successfully',
                'data' => $faq
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to create FAQ',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a single FAQ
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

    /**
     * Update an existing FAQ
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'question' => 'sometimes|required|string|max:255',
                'answer' => 'sometimes|required|string',
                'order' => 'nullable|integer|min:0',
                'status' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Validation failed',
                    'data' => $validator->errors()
                ], 422);
            }

            $faq = $this->appFaq->update($id, $request->all());

            if (!$faq) {
                return response()->json([
                    'error' => true,
                    'message' => 'FAQ not found',
                    'data' => null
                ], 404);
            }

            return response()->json([
                'error' => false,
                'message' => 'FAQ updated successfully',
                'data' => $faq
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to update FAQ',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a FAQ
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $result = $this->appFaq->deleteById($id);

            if (!$result) {
                return response()->json([
                    'error' => true,
                    'message' => 'FAQ not found',
                    'data' => null
                ], 404);
            }

            return response()->json([
                'error' => false,
                'message' => 'FAQ deleted successfully',
                'data' => null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to delete FAQ',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle FAQ status (active/inactive)
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleStatus($id)
    {
        try {
            $faq = $this->appFaq->toggleStatus($id);

            return response()->json([
                'error' => false,
                'message' => 'FAQ status updated successfully',
                'data' => $faq
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to update FAQ status',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reorder FAQs
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reorder(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'faqs' => 'required|array',
                'faqs.*.id' => 'required|integer|exists:app_faqs,id',
                'faqs.*.order' => 'required|integer|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => 'Validation failed',
                    'data' => $validator->errors()
                ], 422);
            }

            foreach ($request->faqs as $faqData) {
                $this->appFaq->updateOrder($faqData['id'], $faqData['order']);
            }

            return response()->json([
                'error' => false,
                'message' => 'FAQs reordered successfully',
                'data' => null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to reorder FAQs',
                'data' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get FAQ statistics
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function statistics()
    {
        try {
            $totalFaqs = $this->appFaq->builder()->count();
            $activeFaqs = $this->appFaq->builder()->where('status', 1)->count();
            $inactiveFaqs = $this->appFaq->builder()->where('status', 0)->count();

            return response()->json([
                'error' => false,
                'message' => 'Statistics retrieved successfully',
                'data' => [
                    'total_faqs' => $totalFaqs,
                    'active_faqs' => $activeFaqs,
                    'inactive_faqs' => $inactiveFaqs,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Failed to retrieve statistics',
                'data' => $e->getMessage()
            ], 500);
        }
    }
}



