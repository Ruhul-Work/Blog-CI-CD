<?php
namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Point;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ShareController extends Controller
{
    public function validateShare(Request $request, $slug)
    {
        if ($request->has('ref')) {
            try {
                $userId = unpack('J', hex2bin($request->query('ref')))[1];

                $blog = Blog::where('slug', $slug)->first();
                if (! $blog) {
                    return redirect()->route('blogsDetails.show', ['slug' => $slug])
                        ->with('error', 'Blog not found.');
                }

                // Get HTTP Referrer, User-Agent, and IP
                $httpReferrer = $request->headers->get('referer');
                $userAgent    = $request->headers->get('user-agent');
                $userIp       = $request->ip();

                // 🚨 Check if the click is from a bot
                if ($this->isBot($userAgent)) {
                    return redirect()->route('blogsDetails.show', ['slug' => $slug])
                        ->with('error', 'Automated bot detected. No points awarded.');
                }

                // 🚨 Validate referrer (or allow WhatsApp)
                if (! $httpReferrer && $request->has('ref')) {
                    // Allow WhatsApp & direct shares when 'ref' is present
                } else if (! $httpReferrer || ! $this->isValidReferrer($httpReferrer)) {
                    return redirect()->route('blogsDetails.show', ['slug' => $slug])
                        ->with('error', 'Invalid share attempt. Please share through a valid platform.');
                }

                // 🚨 Prevent spammy repeated clicks
                if ($this->isFrequentClick($userId, $blog->id)) {
                    return redirect()->route('blogsDetails.show', ['slug' => $slug])
                        ->with('error', 'Suspicious activity detected. No points awarded.');
                }

                // ✅ Record share if all checks pass
                $this->recordShare($userId, $slug);

                return redirect()->route('blogsDetails.show', ['slug' => $slug])
                    ->with('message', 'Thanks for sharing!');
            } catch (\Exception $e) {
                return redirect()->route('blogsDetails.show', ['slug' => $slug])
                    ->with('error', 'Invalid reference data.');
            }
        }

        return redirect()->route('blogsDetails.show', ['slug' => $slug]);
    }

    private function isFrequentClick($userId, $blogId)
    {
        $timeFrame = now()->subMinutes(10); // Check last 10 minutes

        // Ensure user is not clicking the **same blog too often**
        $recentClick = Point::where('user_id', $userId)
            ->where('blog_id', $blogId)
            ->where('created_at', '>=', $timeFrame)
            ->exists();

        return $recentClick;
    }

    private function isBot($userAgent)
    {
        $botKeywords = [
            'bot', 'crawl', 'spider', 'slurp', 'curl', 'fetch', 'python', 'postman',
            'googlebot', 'bingbot', 'yandex', 'duckduckbot', 'baiduspider', 'facebookexternalhit',
            'facebot', 'linkedinbot', 'telegrambot',
        ];

        if (! $userAgent) {
            return false; // Allow empty User-Agent (some real users hide this)
        }

        foreach ($botKeywords as $bot) {
            if (stripos($userAgent, $bot) !== false) {
                return true;
            }
        }

        return false;
    }

    protected function recordShare($userId, $blogSlug)
    {
        $blog = Blog::where('slug', $blogSlug)->first();

        if ($blog) {
            $today = Carbon::today();

            $dailyShares = Point::where('user_id', $userId)
                ->whereDate('created_at', $today)
                ->count();

            if ($dailyShares >= 10) {
                return;
            }

            $alreadyShared = Point::where('user_id', $userId)
                ->where('blog_id', $blog->id)
                ->whereDate('created_at', $today)
                ->exists();

            if (! $alreadyShared) {
                $blog->incrementShareCount();

                // Get share point value dynamically
                $sharePointValue = config('app.share_point_value');

                Point::create([
                    'user_id'    => $userId,
                    'blog_id'    => $blog->id,
                    'debit'      => $sharePointValue,
                    'credit'     => 0,
                    'created_by' => $userId,
                    'ip_address' => request()->ip(), // Store user IP for tracking
                ]);
            }
        }
    }

    private function isValidReferrer($httpReferrer)
    {
        $allowedDomains = [
            'facebook.com', 'm.facebook.com', 'web.facebook.com',
            'api.whatsapp.com', 'web.whatsapp.com', 'www.linkedin.com',
        ];

        if (empty($httpReferrer)) {
            return true; // Allow WhatsApp & direct shares
        }

        foreach ($allowedDomains as $domain) {
            if (strpos($httpReferrer, $domain) !== false) {
                return true;
            }
        }

        return false;
    }

}
