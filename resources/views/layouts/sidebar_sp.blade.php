<div id="accordion-sp">
    <p class="text-center">Order</p>
    <tr>
        <td>
            <div class="sidebar-item-color h4 text-center"><a href="{{route('requestedLists.index')}}">注文依頼リスト</a></div>
        </td>
    </tr>
    <tr>
        <td>
            <div class="sidebar-item-color h4 text-center"><a href="{{route('orderedLists.index')}}">発注済みリスト</a></div>
        </td>
    </tr>
    <tr>
        <td>
            <div class="sidebar-item-color h4 text-center"><a href="{{route('purchasedLists.index')}}">購入実績</a></div>
        </td>
    </tr>
    <tr>
        <td>
            <div class="sidebar-item-color h4 text-center"><a href="{{route('unregisteredModels.index')}}">未登録名一覧</a></div>
        </td>
    </tr>
    <tr>
        <td>
            <div class="sidebar-item-color h4 text-center"><a href="{{route('jobs.index')}}">Job一覧</a></div>
        </td>
    </tr>
    <tr>
        <td>
            <div class="sidebar-item-color h4 text-center"><a href="{{route('parts.kensaku')}}">部品実装検索</a></div>
        </td>
    </tr>
    <tr>
        <td>
            <div class="sidebar-item-color h4 text-center"><a href="{{route('detailedBoards.kensaku')}}">在庫基板詳細記録</a></div>
        </td>
    </tr>

    <p class="text-center">Address</p>
    <tr>
        <td>
            <div class="sidebar-item-color h4 text-center"><a href="{{route('companies.index')}}">会社一覧</a></div>
        </td>
    </tr>
    <tr>
        <td>
            <div class="sidebar-item-color h4 text-center"><a href="{{route('individuals.index')}}">個人一覧</a></div>
        </td>
    </tr>

    <p class="text-center">Board</p>
    <tr>
        <td>
            <div class="sidebar-item-color h4 text-center"><a href="{{route('printCircuitBoards.index')}}">生基板一覧</a></div>
        </td>
    </tr>
    <tr>
        <td>
            <div class="sidebar-item-color h4 text-center"><a href="{{route('parts.index')}}">部品一覧</a></div>
        </td>
    </tr>
    <tr>
        <td>
            <div class="sidebar-item-color h4 text-center"><a href="{{route('others.index')}}">その他</a></div>
        </td>
    </tr>
    <tr>
        <td>
            <div class="sidebar-item-color h4 text-center"><a href="{{route('detailedBoards.index')}}">在庫基板詳細記録</a></div>
        </td>
    </tr>

    <p class="text-center">Other</p>
    <tr>
        <td>
            <div class="sidebar-item-color h4 text-center"><a href="{{route('chatgpt.index')}}">ChatGPT</a></div>
        </td>
    </tr>
    <tr>
        <td>
            <div class="sidebar-item-color h4 text-center"><a href="{{route('configs.editAll')}}">設定</a></div>
        </td>
    </tr>
</div>

<script src="{{ mix('js/ToggleSidebar.js') }}"></script>
