<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Menu;
use App\Models\SubMenu;
use App\Models\ChildMenu;
use App\Models\MegaMenu;
use App\Models\MenuSubmenu;
use App\Models\MenuMegaCombine;
use App\Models\Author;
use App\Models\Publisher;
use App\Models\Category;

class MenuController extends Controller
{
    //menu
    public function index()
    {

        return view('backend.modules.menu.index');
    }

    public function searchRoutes(Request $request)
    {
        $search = $request->input('q');
        $select2Json = [];
        
        $routes = [
                    ['name' => 'home page', 'url' => route('home')],
                    // ['name' => 'section.products', 'url' => route('section.products')],
                    // ['name' => 'getSectionContent', 'url' => route('getSectionContent')],
                    ['name' => 'category page', 'url' => route('category.all')],
                    // ['name' => 'category single', 'url' => route('category.single', ['slug' => 'sample-slug'])],
                    // ['name' => 'category.products', 'url' => route('category.products', ['slug' => 'sample-slug'])],
                    // ['name' => 'category', 'url' => route('category.search')],
                    
                
                    ['name' => 'author page', 'url' => route('author.all')],
                    ['name' => 'publisher page', 'url' => route('publisher.all')],
                    ['name' => 'campaign page', 'url' => route('campaign.all')],
                    ['name' => 'stationary page', 'url' => route('stationary.index')],
                    ['name' => 'about page', 'url' => route('about')],
                    ['name' => 'privacy-policy', 'url' => route('privacy-policy')],
                    ['name' => 'terms', 'url' => route('terms')],
                    ['name' => 'contact', 'url' => route('contact')],
                    ['name' => 'blogs page', 'url' => route('blogs.all')],
                    // Add more routes if necessary
                ];


        
            foreach ($routes as $route) {
                if (stripos($route['name'], $search) !== false) {
                    $select2Json[] = [
                        'value' => $route['url'],
                        'text' => $route['name'],
                    ];
                }
            }
        
        // Fetch categories
        $categories = Category::select('name', 'id', 'slug')
            ->where('name', 'like', '%' . $search . '%')
            ->get();
        foreach ($categories as $single) {
            $select2Json[] = [
                'value' => route('category.single', ['slug' => $single->slug ?? $single->id]),
                'text' => $single->name,
            ];
        }
        // Fetch authors
        $authors = Author::select('name', 'id', 'slug')
            ->where('name', 'like', '%' . $search . '%')
            ->get();

        foreach ($authors as $single) {
            $select2Json[] = [
                'value' => route('author.single', ['slug' => $single->slug ?? $single->id]),
                'text' => $single->name,
            ];
        }
        // Fetch publishers
        $publishers = Publisher::select('name', 'id', 'slug')
            ->where('name', 'like', '%' . $search . '%')
            ->get();

        foreach ($publishers as $single) {
            $select2Json[] = [
                'value' => route('publisher.single', ['slug' => $single->slug ?? $single->id]),
                'text' => $single->name,
            ];
        }

        // $appUrl = env('APP_URL');
        // dd($appUrl);

        // $domain = '_DOMAIN_';

        // foreach ($select2Json as &$item) {

        //     if (strpos($item['value'], $appUrl) !== false) {

        //         $item['value'] = str_replace($appUrl, $domain, $item['value']);
        //     }
        // }
        // unset($item);
        return response()->json($select2Json);
    }
    // create menu
    public function create()
    {

        $submenus = SubMenu::orderBy('created_at', 'desc')->get();
        $childmenus = childMenu::orderBy('created_at', 'desc')->get();
        return view('backend.modules.menu.create', compact('submenus', 'childmenus'));
    }
    //store menu
    public function store(Request $request)
    {

        $rules = [
            'name' => 'required',
            'm_type' => 'required',
            'link' => 'nullable',
            'submenu_id' => 'nullable',
            'childmenu_id' => 'nullable',
            'status' => 'required',
        ];
        $messages = [
            'name' => 'Menu Name is required',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }
        $menu = new Menu();
        $menu->name = $request->name;

        $menu->menu_type = $request->m_type;
        $menu->sub_menu_id = $request->submenu_id;
        $menu->child_id = $request->childmenu_id;
        $menu->status = $request->status;
        // $appUrl = env('APP_URL');

        $appUrl = env('APP_URL');
        $domain = '_DOMAIN_/';

        // Check if the input link contains the APP_URL
        if (strpos($request->link, $appUrl) !== false) {
            // Replace APP_URL with the placeholder domain
            $request->link = str_replace($appUrl, $domain, $request->link);
        } else {
            // If the input link doesn't contain APP_URL, prepend it
            $request->link = $request->link;
        }

        $menu->link = $request->link;
        //dd($menu->link);



        if ($request->hasFile('icon')) {
            $path = 'uploads/menu/icon/' . date('Y/m/d') . '/';
            $imageName = uniqid() . '.webp';
            $request->file('icon')->move($path, $imageName);
            $menu->icon = $path . $imageName;
        }

        $menu->save();
        $menu->sort_order = $menu->id;
        $menu->save();
        return response()->json([
            'message' => 'Menu Saved SuccessFully'
        ]);
    }
    //menu datatable with sort
    //sections datatable
    public function ajaxIndex(Request $request)
    {
        $columns = [
            'id',
        ];

        $draw = $request->draw;
        $row = $request->start;
        $rowPerPage = $request->length;
        $columnIndex = $request->order[0]['column'];
        $columnName = $columns[$columnIndex] ?? $columns[0];
        $columnSortOrder = $request->order[0]['dir'];
        $searchValue = $request->search['value'];
        $query = Menu::with('user')->orderBy('sort_order', 'asc');
        if (!empty($searchValue)) {
            $query->where('name', 'like', '%' . $searchValue . '%');
        }
        $totalDbMenus = $query->count(); // Count total records without pagination
        $allMenus = $query->orderBy($columnName, $columnSortOrder)
            ->skip($row)
            ->take($rowPerPage)
            ->get();
        $allData = [];
        foreach ($allMenus as $key => $menu) {
            $menuType = '';
            $hasSubMenu = MenuSubmenu::where('menu_id', $menu->id)->exists();
            $hasMegaMenu = MenuMegaCombine::where('menu_id', $menu->id)->exists();
            if ($menu->menu_type == 'Mega') {
                if ($hasMegaMenu) {
                    $menuType = '<p style="margin-bottom: 0;" class="fw-bold">Mega Menu
                         <a data-bs-toggle="tooltip" data-bs-placement="top" title="view" class="text-primary ms-1" href="' . route("menus.mega.view", ['id' => encrypt($menu->id)]) . '">
                                <i class="fa fa-eye fa-lg"></i>
                            </a>
                        </p>';
                } else {
                    $menuType = '<p style="margin-bottom: 0;" class="fw-bold">Mega Menu
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Mega Menu" class="text-info ms-1" href="' . route("menus.mega.add", ['id' => encrypt($menu->id)]) . '">
                            <i class="fa fa-plus fa-lg"></i>
                        </a>
                    </p>';
                }
            } elseif ($menu->menu_type == 'General') {
                $menuType = '<p><a class="badges status-badge text-white" href="javascript:void(0)">General</a></p>';
            } elseif ($menu->menu_type == 'Sub_Menu') {
                if ($hasSubMenu) {

                    $menuType = '<p style="margin-bottom: 0;" class="fw-bold">Sub Menu
                     <a data-bs-toggle="tooltip" data-bs-placement="top" title="view" class="text-primary ms-1" href="' . route("menus.submenu.view", ['id' => encrypt($menu->id)]) . '">
                            <i class="fa fa-eye fa-lg"></i>
                        </a>
                    </p>';
                } else {

                    $menuType = '<p style="margin-bottom: 0;" class="fw-bold">Sub Menu
                     <a data-bs-toggle="tooltip" data-bs-placement="top" title="Add Submenu" class="text-primary ms-1" href="' . route("menus.submenu.add", ['id' => encrypt($menu->id)]) . '">
                            <i class="fa fa-plus fa-lg"></i>
                        </a>
                    </p>';
                }
            }
            $dragIcon = '<i style="cursor: pointer;" class="fas fa-arrows-alt drag-handle"></i>';
            $statusHtml = '<input type="checkbox" id="status_' . $menu->id . '" class="check" ' . ($menu->status == 1 ? 'checked' : '') . '>
             <label style="width: 42px !important;" for="status_' . $menu->id . '" class="checktoggle changeStatus" data-menu-id="' . $menu->id . '"></label>';
            $data = [];
            $data[] = $dragIcon;
            $data[] = ++$key + $row;
            $data[] = '<div class="productimgname">
                 <a href="javascript:void(0);">' . ($menu->name ?? '') . '</a>
             </div>';
            if ($menu->menu_type == 'General') {

                $newLink=str_replace("_DOMAIN_/",env('APP_URL'), $menu->link);
                $data[] = '<a class="btn btn-danger p-2" href="' . $newLink . '">Link</a>';
            } else {
                $data[] = '<a class="btn btn-danger p-2" href="javascript:void(0)">No Link</a>';
            }

            $data[] = $menuType;
            $data[] = $statusHtml;
            $data[] = $menu->user->name ?? '';

            $data[] = '
             <div class="action-table-data">
                 <div class="edit-delete-action">
                 <a class="btn btn-info me-2 p-2" href="' . route("menus.edit", ['id' => encrypt($menu->id)]) . '">
                 <i  class="fa fa-edit text-white"></i>
                 </a>
                     <a class="btn btn-danger delete-btn p-2" href="' . route("menus.destroy", ['id' => encrypt($menu->id)]) . '">
                         <i  class="fa fa-trash text-white"></i>
                     </a>
                 </div>
             </div>';
            $data['DT_RowId'] = 'row_' . $menu->id;
            $allData[] = $data;
        }
        $response = [
            "draw" => intval($draw),
            "iTotalRecords" => $totalDbMenus,
            "iTotalDisplayRecords" => $totalDbMenus,
            "aaData" => $allData,
        ];
        return response()->json($response);
    }
    // menus destroy
    public function menuDestroy($id)
    {
        $id = decrypt($id);
        $menu = Menu::find($id);

        if (!$menu) {
            return response()->json(['error' => 'Menu Not Found'], 404);
        }

        if ($menu->menu_type == 'Mega') {
            $megaMenu = MenuMegaCombine::where('menu_id', $menu->id)->get();
            foreach ($megaMenu as $megaId) {
                $megaId->delete();
            }
        } elseif ($menu->menu_type == 'Sub_Menu') {
            $subMenu = MenuSubmenu::where('menu_id', $menu->id)->get();
            foreach ($subMenu as $subId) {
                $subId->delete();
            }
        }
        if (isset($menu->icon) && file_exists($menu->icon)) {
            unlink($menu->icon);
        }
        $menu->delete();
        return response()->json(['message' => 'Deleted successfully', 'success' => true], 200);
    }
    // menu with submenu
    public function menuSubmenuView($id)
    {
        $menuId = decrypt($id);
        $menu = Menu::find($menuId);
        return view('backend.modules.menu.menu-with-submenu', compact('menu'));
    }
    public function menuSorting(Request $request)
    {
        // Validate the request
        $request->validate([
            'tableId' => 'required|string',
            'newOrder' => 'required|array',
            'newOrder.*.id' => 'required|integer',
            'newOrder.*.newPosition' => 'required|integer',
        ]);
        $oldId = $request->newOrder[0]['id'];

        $newId = $request->newOrder[1]['id'];

        // Get the two table records
        $oldSort = Menu::findOrFail($oldId);

        $newSort = Menu::findOrFail($newId);
        // dd($table1,$table2);

        // Swap their sort_order values
        $temp = $oldSort->sort_order;

        $oldSort->sort_order = $newSort->sort_order;
        $newSort->sort_order = $temp;

        $oldSort->save();
        $newSort->save();


        return response()->json(['success' => true]);
    }
    //  menu edit
    public function edit($id)
    {
        $Id = decrypt($id);

        $singlemenu = Menu::find($Id);

        return view('backend.modules.menu.edit', compact('singlemenu'));
    }
    // update
    public function update(Request $request)
    {
        $rules = [
            'name' => 'required',
            // 'm_type' => 'required',
            'link' => 'nullable',
            'submenu_id' => 'nullable',
            'childmenu_id' => 'nullable',
            'status' => 'required',
        ];

        $messages = [
            'name' => 'Menu Name is required',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $menu = Menu::find($request->id);

        $menu->name = $request->name;

        if (!$request->link) {
            $menu->link = $menu->link;
        }

        if ($request->link) {
            $menu->link = $request->link;
        }

        $menu->sub_menu_id = $request->submenu_id;

        $menu->child_id = $request->childmenu_id;

        $menu->status = $request->status;

        if ($request->hasFile('icon')) {

            if (isset($menu->icon)) {
                $oldImagePath = $menu->icon;
                unlink($oldImagePath);
            }

            $path = 'uploads/menu/icon/' . date('Y/m/d') . '/';
            $imageName = uniqid() . '.webp';
            $request->file('icon')->move($path, $imageName);
            $menu->icon = $path . $imageName;
        }

        $menu->save();

        return response()->json([
            'message' => 'Menu Update SuccessFully'
        ]);
    }
    //  menu status
    public function updateStatus(Request $request)
    {
        $id = $request->id;
        $menu = Menu::findOrFail($id);
        // Toggle the status
        $menu->status = $menu->status == 1 ? 0 : 1;
        // Save the category
        $menu->save();
        // Return a response
        return response()->json(['message' => 'Status updated successfully'], 200);
    }
    //store submenu
    public function storeSubMenu(Request $request)
    {
        $rules = [
            'name' => 'required',
            'link' => 'nullable',
        ];

        $messages = [
            'name' => 'Sub Menu Name is required',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $submenu = new SubMenu();
        $submenu->name = $request->name;



        $appUrl = env('APP_URL');
        $domain = '_DOMAIN_/';

        // Check if the input link contains the APP_URL
        if (strpos($request->link, $appUrl) !== false) {
            // Replace APP_URL with the placeholder domain
            $request->link = str_replace($appUrl, $domain, $request->link);
        } else {
            // If the input link doesn't contain APP_URL, prepend it
            $request->link = $request->link;
        }

        $submenu->link = $request->link;
        // dd($request->link);

        $submenu->save();

        return response()->json([
            'message' => 'SubMenu Saved SuccessFully'
        ]);
    }
    //
    public function storeChildMenu(Request $request)
    {
        $rules = [
            'name' => 'required',
            'link' => 'nullable',
        ];

        $messages = [
            'name' => 'Child  Menu Name is required',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $childmenu = new ChildMenu();

        $childmenu->name = $request->name;
        $childmenu->link = $request->link;
        $childmenu->save();

        return response()->json([
            'message' => 'Child Menu Saved SuccessFully'
        ]);
    }
    // sub menu add
    public function subMenu($id)
    {
        $Id = decrypt($id);
        $menu = Menu::find($Id);
        // add here menu wise sub menu
        return view('backend.modules.menu.add-submenu', compact('menu'));
    }
    public function subMenuAjax(Request $request)
    {
        $columns = ['id', 'name', 'link', 'created_at'];
        $draw = intval($request->draw);
        $row = intval($request->start);
        $rowPerPage = intval($request->length);
        $columnIndex = intval($request->order[0]['column']);
        $columnName = $columns[$columnIndex] ?? $columns[0];
        $columnSortOrder = $request->order[0]['dir'];
        $searchValue = $request->search['value'];

        $query = SubMenu::query()->orderBy('created_at', 'desc');

        if (!empty($searchValue)) {
            $query->where('name', 'like', '%' . $searchValue . '%');
        }

        $totalDbSubMenus = $query->count();
        $allSubMenus = $query->orderBy($columnName, $columnSortOrder)
            ->skip($row)
            ->take($rowPerPage)
            ->get();

        $allData = [];
        $counter = $row + 1;
        foreach ($allSubMenus as $submenu) {

            $checkMark = '<td><label class="checkboxs"><input type="checkbox" data-value="' . $submenu->id . '"><span class="checkmarks"></span></label></td>';

            $data = [];
            $data[] = $checkMark;
            $data[] = $counter++;
            $data[] = '<span class="badge-linesuccess">' . $submenu->name . '</span>';
            $data[] = '<a class="btn btn-danger p-2" href="' . $submenu->link . '">Link</a>';
            $data[] = '
                <div class="action-table-data">
                    <div class="edit-delete-action">
                        <a class="btn btn-danger delete-btn p-2" href="' . route("menus.submenu.destroy", ['id' => encrypt($submenu->id)]) . '">
                            <i class="fa fa-trash text-white"></i>
                        </a>
                    </div>
                </div>';
            $allData[] = $data;
        }
        $response = [
            "draw" => $draw,
            "iTotalRecords" => $totalDbSubMenus,
            "iTotalDisplayRecords" => $totalDbSubMenus,
            "aaData" => $allData,
        ];
        return response()->json($response);
    }
    public function menuSubmenuStore(Request $request)
    {
        // dd($menu);
        $menu = Menu::find(1);
        $menu->submenus()->attach([1, 2, 3]);
    }
    public function subMenuStore(Request $request)
    {

        $ids = $request->ids;
        $menuID = $request->menu_id;
        $menu = Menu::find($menuID);
        if (!$menu) {
            return response()->json(['message' => 'No coupon found.'], 404);
        }
        foreach ($ids as $id) {
            $existingMenu = MenuSubmenu::where('menu_id', $menu->id)
                ->where('submenu_id', $id)
                ->first();
            if (!$existingMenu) {
                $menuSubmenu = new MenuSubmenu();
                $menuSubmenu->menu_id = $menu->id;
                $menuSubmenu->submenu_id = $id;
                $menuSubmenu->save();
                $menuSubmenu->sort_order = $menuSubmenu->id;
                $menuSubmenu->save();
            }
        }
        return response()->json(['message' => 'Added successfully'], 200);
    }
    public function menuSubmenuAjax(Request $request)
    {
        $columns = ['id', 'name']; // Define columns for ordering
        $draw = $request->draw;
        $row = $request->start;
        $rowPerPage = $request->length;
        $columnIndex = $request->order[0]['column'] ?? 0;
        $columnName = $columns[$columnIndex] ?? 'id';
        $columnSortOrder = $request->order[0]['dir'] ?? 'asc';
        $searchValue = $request->search['value'] ?? '';
        try {
            // Find the menu by ID
            $menu = Menu::with('submenus')->findOrFail($request->id);

            // Query submenus with pivot data
            $query = $menu->submenus()->withPivot('id', 'sort_order')->orderBy('sort_order', 'asc');

            // Apply filtering if search value exists
            if ($searchValue) {
                $query->where('name', 'like', "%$searchValue%");
            }

            // Total records without pagination
            $totalDbProducts = $query->count();

            // Fetch paginated records
            $allProducts = $query->orderBy($columnName, $columnSortOrder)
                ->skip($row)
                ->take($rowPerPage)
                ->get();

            $allData = [];
            foreach ($allProducts as $key => $submenu) {
                $subMenuId = $submenu->pivot->id;
                $dragIcon = '<i style="cursor: pointer;" class="fas fa-arrows-alt drag-handle"></i>';
                $data = [];
                $data[] = $dragIcon;
                $data[] = ++$key + $row;
                $data[] = $submenu->name ?? '';
                $data[] = '<a class="btn btn-danger p-2" href="' . $submenu->link . '">Link</a>';
                $data[] = '
                    <div class="action-table-data">
                        <div class="edit-delete-action">
                            <a class="btn btn-danger delete-btn p-2" href="' . route("menus.submenu-menu.destroy", ['id' => $subMenuId]) . '">
                                <i class="fa fa-trash text-white"></i>
                            </a>
                        </div>
                    </div>';
                $data['DT_RowId'] = 'row_' . $subMenuId;
                $allData[] = $data;
            }

            // Prepare JSON response for DataTables
            $response = [
                "draw" => intval($draw),
                "iTotalRecords" => $totalDbProducts,
                "iTotalDisplayRecords" => $totalDbProducts,
                "aaData" => $allData,
            ];

            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function menuSubmenusorting(Request $request)
    {
        // Validate the request
        $request->validate([
            'tableId' => 'required|string',
            'newOrder' => 'required|array',
            'newOrder.*.id' => 'required|integer',
            'newOrder.*.newPosition' => 'required|integer',
        ]);
        $oldId = $request->newOrder[0]['id'];

        $newId = $request->newOrder[1]['id'];

        // Get the two table records
        $oldSort = MenuSubmenu::findOrFail($oldId);

        $newSort = MenuSubmenu::findOrFail($newId);

        $temp = $oldSort->sort_order;

        $oldSort->sort_order = $newSort->sort_order;

        $newSort->sort_order = $temp;
        $oldSort->save();
        $newSort->save();

        return response()->json(['success' => true]);
    }
    public function subMenuDestroy(Request $request, $id)
    {
        $Id = decrypt($id);
        $subMenu = SubMenu::find($Id);
        if (!$subMenu) {
            return response()->json(['error' => 'SubMenu Not Found'], 404);
        }
        $subMenuCombine = MenuSubmenu::where('submenu_id', $subMenu->id)->get();

        if ($subMenuCombine->isNotEmpty()) {
            foreach ($subMenuCombine as $subId) {
                $subId->delete();
            }
        }
        $subMenu->delete();
        return response()->json(['message' => 'Deleted successfully', 'success' => true], 200);
    }
    public function subMenuwithMenu($id)
    {
        $subMenuCombine = MenuSubmenu::where('id', $id)->first();
        $subMenuCombine->delete();
        return response()->json(['message' => 'Deleted successfully', 'success' => true], 200);
    }

    // mega menu methods
    public function megaMenu($id)
    {

        $Id = decrypt($id);
        $menu = Menu::find($Id);
        // add here menu wise sub menu
        return view('backend.modules.menu.mega-menu', compact('menu'));
    }
    public function megaMenuStore(Request $request)
    {

        $rules = [
            'name' => 'required',
            'link' => 'nullable',
        ];
        $messages = [
            'name' => 'Name is required',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $submenu = new MegaMenu();
        $submenu->name = $request->name;




        $appUrl = env('APP_URL');
        $domain = '_DOMAIN_/';

        // Check if the input link contains the APP_URL
        if (strpos($request->link, $appUrl) !== false) {
            // Replace APP_URL with the placeholder domain
            $request->link = str_replace($appUrl, $domain, $request->link);
        } else {
            // If the input link doesn't contain APP_URL, prepend it
            $request->link = $request->link;
        }

        $submenu->link = $request->link;

        $submenu->save();

        return response()->json([
            'message' => 'Saved SuccessFully'
        ]);
    }
    // mega menu ajax
    public function megaMenuAjax(Request $request)
    {
        $columns = ['id', 'name', 'link', 'created_at'];
        $draw = intval($request->draw);
        $row = intval($request->start);
        $rowPerPage = intval($request->length);
        $columnIndex = intval($request->order[0]['column']);
        $columnName = $columns[$columnIndex] ?? $columns[0];
        $columnSortOrder = $request->order[0]['dir'];
        $searchValue = $request->search['value'];

        $query = MegaMenu::query()->orderBy('created_at', 'desc');

        if (!empty($searchValue)) {
            $query->where('name', 'like', '%' . $searchValue . '%');
        }

        $totalDbSubMenus = $query->count();
        $allSubMenus = $query->orderBy($columnName, $columnSortOrder)
            ->skip($row)
            ->take($rowPerPage)
            ->get();

        $allData = [];
        $counter = $row + 1;
        foreach ($allSubMenus as $submenu) {

            $checkMark = '<td><label class="checkboxs"><input type="checkbox" data-value="' . $submenu->id . '"><span class="checkmarks"></span></label></td>';
            $data = [];
            $data[] = $checkMark;
            $data[] = $counter++;
            $data[] = '<span class="badge-linesuccess">' . $submenu->name . '</span>';
            $data[] = '<a class="btn btn-danger p-2" href="' . $submenu->link . '">Link</a>';
            $data[] = '
                <div class="action-table-data">
                    <div class="edit-delete-action">
                        <a class="btn btn-danger delete-btn p-2" href="' . route("menu.mega-menu-delete", ['id' => encrypt($submenu->id)]) . '">
                            <i class="fa fa-trash text-white"></i>
                        </a>
                    </div>
                </div>';
            $allData[] = $data;
        }
        $response = [
            "draw" => $draw,
            "iTotalRecords" => $totalDbSubMenus,
            "iTotalDisplayRecords" => $totalDbSubMenus,
            "aaData" => $allData,
        ];
        return response()->json($response);
    }
    // megamenustore
    public function menusMegamenuCreate(Request $request)
    {

        $ids = $request->ids;
        $menuID = $request->menu_id;
        $menu = Menu::find($menuID);
        if (!$menu) {
            return response()->json(['message' => 'No Menu found.'], 404);
        }
        foreach ($ids as $id) {
            $existingMenu = MenuMegaCombine::where('menu_id', $menu->id)
                ->where('mega_menu_id', $id)
                ->first();
            if (!$existingMenu) {
                $menuMegamenu = new MenuMegaCombine();
                $menuMegamenu->menu_id = $menu->id;
                $menuMegamenu->mega_menu_id = $id;
                $menuMegamenu->save();
                $menuMegamenu->sort_order = $menuMegamenu->id;
                $menuMegamenu->save();
            }
        }
        return response()->json(['message' => 'Added successfully'], 200);
    }
    public function menusMegaView($id)
    {
        $Id = decrypt($id);

        $menu = Menu::find($Id);
        // add here menu wise sub menu
        return view('backend.modules.menu.megamenu-view', compact('menu'));
    }
    public function megaMenuViewAjax(Request $request)
    {
        $columns = ['id', 'name'];
        $draw = $request->draw;
        $row = $request->start;
        $rowPerPage = $request->length;
        $columnIndex = $request->order[0]['column'] ?? 0;
        $columnName = $columns[$columnIndex] ?? 'id';
        $columnSortOrder = $request->order[0]['dir'] ?? 'asc';
        $searchValue = $request->search['value'] ?? '';
        try {
            // Find the menu by ID
            $menu = Menu::with('megamenu')->findOrFail($request->id);
            // Query submenus with pivot data
            $query = $menu->megamenu()->withPivot('id', 'sort_order')->orderBy('sort_order', 'asc');
            // Apply filtering if search value exists
            if ($searchValue) {
                $query->where('name', 'like', "%$searchValue%");
            }
            // Total records without pagination
            $totalDbMegaMenus = $query->count();
            // Fetch paginated records
            $allMegaMenus = $query->orderBy($columnName, $columnSortOrder)
                ->skip($row)
                ->take($rowPerPage)
                ->get();

            $allData = [];
            foreach ($allMegaMenus as $key => $mega) {
                $megaMenuId = $mega->pivot->id;
                $dragIcon = '<i style="cursor: pointer;" class="fas fa-arrows-alt drag-handle"></i>';
                $data = [];
                $data[] = $dragIcon;
                $data[] = ++$key + $row;
                $data[] = $mega->name ?? '';
                $data[] = '<a class="btn btn-danger p-2" href="' . $mega->link . '">Link</a>';
                $data[] = '
                    <div class="action-table-data">
                        <div class="edit-delete-action">
                            <a class="btn btn-danger delete-btn p-2" href="' . route("menus.single-menu-mega.delete", ['id' => $megaMenuId]) . '">
                                <i class="fa fa-trash text-white"></i>
                            </a>
                        </div>
                    </div>';
                $data['DT_RowId'] = 'row_' . $megaMenuId;
                $allData[] = $data;
            }

            // Prepare JSON response for DataTables
            $response = [
                "draw" => intval($draw),
                "iTotalRecords" => $totalDbMegaMenus,
                "iTotalDisplayRecords" => $totalDbMegaMenus,
                "aaData" => $allData,
            ];
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function menuMegaMenusorting(Request $request)
    {
        // Validate the request
        $request->validate([
            'tableId' => 'required|string',
            'newOrder' => 'required|array',
            'newOrder.*.id' => 'required|integer',
            'newOrder.*.newPosition' => 'required|integer',
        ]);
        $oldId = $request->newOrder[0]['id'];
        $newId = $request->newOrder[1]['id'];
        // Get the two table records
        $oldSort = MenuMegaCombine::findOrFail($oldId);
        $newSort = MenuMegaCombine::findOrFail($newId);
        $temp = $oldSort->sort_order;
        $oldSort->sort_order = $newSort->sort_order;
        $newSort->sort_order = $temp;
        $oldSort->save();
        $newSort->save();
        return response()->json(['success' => true]);
    }
    // megamenudetroy
    public function megaMenuDestroy($id)
    {

        $Id = decrypt($id);

        $megaMenu = MegaMenu::find($Id);

        $megaMenuCombine = MenuMegaCombine::where('mega_menu_id', $megaMenu->id)->get();

        if ($megaMenuCombine) {

            foreach ($megaMenuCombine as $megaId) {

                $megaId->delete();
            }
        }
        $megaMenu->delete();


        return response()->json(['message' => 'Deleted successfully', 'success' => true], 200);
    }
    // megamenuwith menu delete
    public function megaMenuwithMenu($id)
    {


        $megaMenuCombine = MenuMegaCombine::where('id', $id)->first();

        $megaMenuCombine->delete();

        return response()->json(['message' => 'Deleted successfully', 'success' => true], 200);
    }
}
