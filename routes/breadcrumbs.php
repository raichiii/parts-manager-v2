<?php

// ホーム
Breadcrumbs::for('home', function ($trail) {
    $trail->push('ホーム', route('home'));
});

// Home > Companies
Breadcrumbs::for('companies', function ($trail) {
    $trail->parent('home');
    $trail->push('会社一覧', route('companies.index'));
});

// Home > Companies > {company}
Breadcrumbs::for('company', function ($trail, $company) {
    $trail->parent('companies');
    $trail->push($company->full_name, route('companies.show', [$company]));
});

// Home > Companies > create
Breadcrumbs::for('company.create', function ($trail) {
    $trail->parent('companies');
    $trail->push('作成', route('companies.create'));
});

// Home > Companies > {company} > edit
Breadcrumbs::for('company.edit', function ($trail, $company) {
    $trail->parent('company', $company);
    $trail->push('編集', route('companies.edit', [$company]));
});

// Home > Companies > {company} > requested
Breadcrumbs::for('company.requested', function ($trail, $company) {
    $trail->parent('company', $company);
    $trail->push('注文依頼リスト', route('companies.requestedLists', [$company]));
});

// Home > Companies > {company} > ordered
Breadcrumbs::for('company.ordered', function ($trail, $company) {
    $trail->parent('company', $company);
    $trail->push('発注済みリスト', route('companies.orderedLists', [$company]));
});

// Home > Companies > {company} > offices
Breadcrumbs::for('offices', function ($trail, $company) {
    $trail->parent('company', $company);
    $trail->push('事務所一覧', route('offices.index', [$company]));
});

// Home > Companies > {company} > offices > create
Breadcrumbs::for('office.create', function ($trail, $company) {
    $trail->parent('offices', $company);
    $trail->push('作成', route('offices.create', [$company]));
});

// Home > Companies > {company} > offices > {office}
Breadcrumbs::for('office', function ($trail, $company, $office) {
    $trail->parent('offices', $company);
    $trail->push($office->name, route('offices.show', [$company, $office]));
});

// Home > Companies > {company} > offices > {office} > edit
Breadcrumbs::for('office.edit', function ($trail, $company, $office) {
    $trail->parent('office', $company, $office);
    $trail->push('編集', route('offices.edit', [$company, $office]));
});

// Home > Companies > {company} > customers
Breadcrumbs::for('customers', function ($trail, $company) {
    $trail->parent('company', $company);
    $trail->push('顧客一覧', route('customers.company.index', [$company]));
});

// Home > Companies > {company} > customers > create
Breadcrumbs::for('customer.create', function ($trail, $company) {
    $trail->parent('customers', $company);
    $trail->push('作成', route('customers.company.create', [$company]));
});

// Home > Companies > {company} > offices > {office} > customers > {customer}
Breadcrumbs::for('customer', function ($trail, $company, $office, $customer) {
    $trail->parent('office', $company, $office);
    $trail->push($customer->name, route('customers.show', [$company, $office, $customer]));
});

// Home > Companies > {company} > offices > {office} > customers > {customer} > edit
Breadcrumbs::for('customer.edit', function ($trail, $company, $office, $customer) {
    $trail->parent('customer', $company, $office, $customer);
    $trail->push('編集', route('customers.edit', [$company, $office, $customer]));
});

// Home > Individuals
Breadcrumbs::for('individuals', function ($trail) {
    $trail->parent('home');
    $trail->push('個人一覧', route('individuals.index'));
});

// Home > Individuals > create
Breadcrumbs::for('individual.create', function ($trail) {
    $trail->parent('individuals');
    $trail->push('個人の作成', route('individuals.create'));
});

// Home > Individuals > {individual}
Breadcrumbs::for('individual', function ($trail, $individual) {
    $trail->parent('individuals');
    $trail->push($individual->name, route('individuals.show', [$individual]));
});

// Home > Individuals > {individual} > edit
Breadcrumbs::for('individual.edit', function ($trail, $individual) {
    $trail->parent('individual', $individual);
    $trail->push('編集', route('individuals.edit', [$individual]));
});

// Home > Parts
Breadcrumbs::for('parts', function ($trail) {
    $trail->parent('home');
    $trail->push('部品一覧', route('parts.index'));
});

// Home > Parts > create
Breadcrumbs::for('part.create', function ($trail) {
    $trail->parent('parts');
    $trail->push('部品の作成', route('parts.create'));
});

// Home > Parts > {part}
Breadcrumbs::for('part', function ($trail, $part) {
    $trail->parent('parts');
    $trail->push($part->name, route('parts.show', [$part]));
});

// Home > Parts > {part} > edit
Breadcrumbs::for('part.edit', function ($trail, $part) {
    $trail->parent('part', $part);
    $trail->push('編集', route('parts.edit', [$part]));
});

// Home > Parts > Kensaku
Breadcrumbs::for('parts.kensaku', function ($trail) {
    $trail->parent('home');
    $trail->push('部品実装検索', route('parts.kensaku'));
});

// Home > PrintCircuitBoards
Breadcrumbs::for('printCircuitBoards', function ($trail) {
    $trail->parent('home');
    $trail->push('生基板一覧', route('printCircuitBoards.index'));
});

// Home > PrintCircuitBoards > create
Breadcrumbs::for('printCircuitBoard.create', function ($trail) {
    $trail->parent('printCircuitBoards');
    $trail->push('生基板の作成', route('printCircuitBoards.create'));
});

// Home > PrintCircuitBoards > {printCircuitBoard}
Breadcrumbs::for('printCircuitBoard', function ($trail, $printCircuitBoard) {
    $trail->parent('printCircuitBoards');
    $trail->push($printCircuitBoard->thing->model_no, route('printCircuitBoards.show', [$printCircuitBoard]));
});

// Home > PrintCircuitBoards > {printCircuitBoard} > edit
Breadcrumbs::for('printCircuitBoard.edit', function ($trail, $printCircuitBoard) {
    $trail->parent('printCircuitBoard', $printCircuitBoard);
    $trail->push('編集', route('printCircuitBoards.edit', [$printCircuitBoard]));
});

// Home > PrintCircuitBoards > {printCircuitBoard} > MountedBoards > create
Breadcrumbs::for('mountedBoard.create', function ($trail, $printCircuitBoard) {
    $trail->parent('printCircuitBoard', $printCircuitBoard);
    $trail->push('基板の作成', route('mountedBoards.create', [$printCircuitBoard]));
});

// Home > PrintCircuitBoards > {printCircuitBoard} > MountedBoards > {mountedBoard}
Breadcrumbs::for('mountedBoard', function ($trail, $printCircuitBoard, $mountedBoard) {
    $trail->parent('printCircuitBoard', $printCircuitBoard);
    $version_name = $mountedBoard->version_name == null ? 'なし' : $mountedBoard->version_name;
    $trail->push($version_name . '(バージョン' . $mountedBoard->version . ')', route('mountedBoards.show', [$printCircuitBoard, $mountedBoard]));
});

// Home > PrintCircuitBoards > {printCircuitBoard} > MountedBoards > {mountedBoard} > edit
Breadcrumbs::for('mountedBoard.edit', function ($trail, $printCircuitBoard, $mountedBoard) {
    $trail->parent('mountedBoard', $printCircuitBoard, $mountedBoard);
    $trail->push('編集', route('mountedBoards.edit', [$printCircuitBoard, $mountedBoard]));
});

// Home > DetailedBoards
Breadcrumbs::for('detailedBoards', function ($trail) {
    $trail->parent('home');
    $trail->push('在庫基板の詳細記録', route('detailedBoards.index'));
});

// Home > DetailedBoards > {detailedBoard}
Breadcrumbs::for('detailedBoard', function ($trail, $detailedBoard) {
    $trail->parent('detailedBoards');
    $trail->push($detailedBoard->mountedBoard->printCircuitBoard->name . '(在P-' . $detailedBoard->id . ')', route('detailedBoards.show', [$detailedBoard]));
});

// Home > DetailedBoards > create
Breadcrumbs::for('detailedBoard.create', function ($trail) {
    $trail->parent('detailedBoards');
    $trail->push('在庫基板の詳細記録の作成', route('detailedBoards.create'));
});

// Home > DetailedBoards > {detailedBoard} > edit
Breadcrumbs::for('detailedBoard.edit', function ($trail, $detailedBoard) {
    $trail->parent('detailedBoard', $detailedBoard);
    $trail->push('編集', route('detailedBoards.edit', [$detailedBoard]));
});

// Home > DetailedBoards > Kensaku
Breadcrumbs::for('detailedBoards.kensaku', function ($trail) {
    $trail->parent('home');
    $trail->push('在庫基板詳細記録', route('detailedBoards.kensaku'));
});

// Home > Others
Breadcrumbs::for('others', function ($trail) {
    $trail->parent('home');
    $trail->push('部品一覧', route('others.index'));
});

// Home > Others > create
Breadcrumbs::for('other.create', function ($trail) {
    $trail->parent('others');
    $trail->push('部品の作成', route('others.create'));
});

// Home > Others > {other}
Breadcrumbs::for('other', function ($trail, $other) {
    $trail->parent('others');
    $trail->push($other->name, route('others.show', [$other]));
});

// Home > Others > {other} > edit
Breadcrumbs::for('other.edit', function ($trail, $other) {
    $trail->parent('other', $other);
    $trail->push('編集', route('others.edit', [$other]));
});

// Home > Jobs
Breadcrumbs::for('jobs', function ($trail) {
    $trail->parent('home');
    $trail->push('Job一覧', route('jobs.index'));
});

// Home > Jobs > {job}
Breadcrumbs::for('job', function ($trail, $job) {
    $trail->parent('jobs');
    $trail->push($job->number, route('jobs.show', [$job]));
});

// Home > Jobs > create
Breadcrumbs::for('job.create', function ($trail) {
    $trail->parent('jobs');
    $trail->push('作成', route('jobs.create'));
});

// Home > Jobs > {job} > edit
Breadcrumbs::for('job.edit', function ($trail, $job) {
    $trail->parent('job', $job);
    $trail->push('編集', route('jobs.edit', [$job]));
});

// Home > RequestedLists
Breadcrumbs::for('requestedLists', function ($trail) {
    $trail->parent('home');
    $trail->push('注文依頼リスト', route('requestedLists.index'));
});

// Home > RequestedLists > {requestedList}
Breadcrumbs::for('requestedList', function ($trail, $requestedList) {
    $trail->parent('requestedLists');
    $trail->push($requestedList->id, route('requestedLists.show', [$requestedList]));
});

// Home > RequestedLists > {requestedList} > edit
Breadcrumbs::for('requestedList.edit', function ($trail, $requestedList) {
    $trail->parent('requestedList', $requestedList);
    $trail->push('編集', route('requestedLists.edit', [$requestedList]));
});

// Home > OrderedLists
Breadcrumbs::for('orderedLists', function ($trail) {
    $trail->parent('home');
    $trail->push('発注済みリスト', route('orderedLists.index'));
});

// Home > OrderedLists > {orderedList}
Breadcrumbs::for('orderedList', function ($trail, $orderedList) {
    $trail->parent('orderedLists');
    $trail->push($orderedList->id, route('orderedLists.show', [$orderedList]));
});

// Home > OrderedLists > {orderedList} > edit
Breadcrumbs::for('orderedList.edit', function ($trail, $orderedList) {
    $trail->parent('orderedList', $orderedList);
    $trail->push('編集', route('orderedLists.edit', [$orderedList]));
});

// Home > PurchasedLists
Breadcrumbs::for('purchasedLists', function ($trail) {
    $trail->parent('home');
    $trail->push('購入実績', route('purchasedLists.index'));
});

// Home > PurchasedLists > {purchasedList}
Breadcrumbs::for('purchasedList', function ($trail, $purchasedList) {
    $trail->parent('purchasedLists');
    $trail->push($purchasedList->id, route('purchasedLists.show', [$purchasedList]));
});

// Home > PurchasedLists > {requestedList} > edit
Breadcrumbs::for('purchasedList.edit', function ($trail, $purchasedList) {
    $trail->parent('purchasedList', $purchasedList);
    $trail->push('編集', route('purchasedLists.edit', [$purchasedList]));
});

// Home > UnregisteredModels
Breadcrumbs::for('unregisteredModels', function ($trail) {
    $trail->parent('home');
    $trail->push('未登録名一覧', route('unregisteredModels.index'));
});

// Home > purchaseOrder > edit
Breadcrumbs::for('purchaseOrder.edit', function ($trail) {
    $trail->parent('home');
    $trail->push('編集', route('purchaseOrder.edit'));
});

// Home > AccountLists
Breadcrumbs::for('accountLists.index', function ($trail) {
    $trail->parent('home');
    $trail->push('仕入れ先用アカウント一覧', route('accountLists.index'));
});

// Home > AccountLists
Breadcrumbs::for('accountLists', function ($trail) {
    $trail->parent('home');
    $trail->push('仕入れ先用アカウント一覧', route('accountLists.index'));
});

// Home > AccountLists > create
Breadcrumbs::for('accountList.create', function ($trail) {
    $trail->parent('accountLists');
    $trail->push('作成', route('accountLists.create'));
});

// Home > AccountLists > {AccountList}
Breadcrumbs::for('accountList', function ($trail, $accountList) {
    $trail->parent('accountLists');
    $trail->push($accountList->name, route('accountLists.show', [$accountList]));
});

// Home > AccountLists > {AccountList} > edit
Breadcrumbs::for('accountList.edit', function ($trail, $accountList) {
    $trail->parent('accountList', $accountList);
    $trail->push('編集', route('accountLists.edit', [$accountList]));
});

// Home > TextInfos
Breadcrumbs::for('textInfos.index', function ($trail) {
    $trail->parent('home');
    $trail->push('テキスト情報検索', route('textInfos.index'));
});

// Home > ChatGPT
Breadcrumbs::for('chatgpt.index', function ($trail) {
    $trail->parent('home');
    $trail->push('ChatGPT', route('chatgpt.index'));
});

// Home > Setting
Breadcrumbs::for('configs.editAll', function ($trail) {
    $trail->parent('home');
    $trail->push('設定', route('configs.editAll'));
});
