<div class="sidebar-sticky">
    <div class="list-sidebar">
        <!-- Blog Categories with Checkbox -->
        <div class="sidebar-item mb-4">
            <h4 class="mb-3">ক্যাটাগরি</h4>
            <form id="filterForm">
                
                <ul class="sidebar-category list-unstyled">
                    <li>
                        <label>
                            <input type="checkbox" name="category" value="all" checked>
                            সকল ক্যাটাগরি
                        </label>
                    </li>
                    @foreach ($categories as $category)
                        <li>
                            <label>
                                <input type="checkbox" name="category" value="{{ $category->slug }}">
                                {{ $category->name }}
                            </label>
                        </li>
                    @endforeach
                </ul>
            </form>
        </div>

        <!-- Blog Type Filter -->
        <!--<h4 class="mb-3 mt-4">ব্লগ টাইপ</h4>
        <ul class="sidebar-category list-unstyled">
            <li>
                <label>
                    <input type="radio" name="blog_type" value="premium">
                    প্রিমিয়াম ব্লগ
                </label>
            </li>
            <li>
                <label>
                    <input type="radio" name="blog_type" value="free">
                    ফ্রি ব্লগ
                </label>
            </li>
        </ul>
-->
        <!-- Accordion Filter -->
        <h4 class="mb-3 mt-4">তারিখ অনুসারে ফিল্টার</h4>
        <div class="calendar"></div>
    </div>
</div>
