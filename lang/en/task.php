<?php

return [
    'task' => 'Công việc タスク',
    'module_title' => 'Quản lý task すべてのタスクを管理する',
    'module_description' => 'Tạo công việc và giao việc, theo dõi tiến độ công việc. シンプルなインターフェースで複数のタスクを作成し、従業員に割り当て、タスクの進行状況を追跡します。',
    'no_member_found' => 'Công việc này chưa giao cho ai. このタスクはどのメンバーにも割り当てられていません。',
    'no_checklist_found' => 'Không có check-list nào. タスクにはチェックリストが関連付けられていません。',
    'could_not_perform_if_cancelled' => 'Không thể thực hiện được do công việc đã bị hủy. タスクがキャンセルされたため、この操作を実行できませんでした。',
    'could_not_perform_if_completed' => 'Không thể thực hiện được do công việc đã hoàn thành. タスクが完了したため、この操作を実行できませんでした。',
    'could_not_perform_if_incomplete' => 'Không thể thực hiện được do công việc chưa hoàn thành. タスクが完了していないため、この操作を実行できませんでした。',
    'props' => [
        'code_number' => 'Công việc タスク #',
        'owner' => 'Người tạo オーナー',
        'owned' => 'Người tạo オーナー',
        'favorite' => 'Đánh dấu お気に入り',
        'title' => 'Tiêu đề タイトル',
        'start_date' => 'Ngày bắt đầu 開始日',
        'due_date' => 'Hạn hoàn thành 締切日',
        'due_time' => 'Giờ hoàn thành 締切時間',
        'description' => 'Mô tả 説明',
        'overdue_by' => 'Quá hạn 期限過ぎ :day ngày 日',
    ],
    'statuses' => [
        'cancel' => 'Hủy キャンセル',
        'active' => 'Đang hoạt động アクティブ',
        'complete' => 'Hoàn thành 完了',
        'incomplete' => 'Chưa hoàn thành 未完成',
        'pending' => 'Chưa làm 保留中',
        'completed' => 'Đã hoàn thành 完了',
        'overdue' => 'Quá hạn 締切過ぎ',
    ],
    'checklist' => [
        'checklist' => 'Checklist',
        'module_title' => 'Quản lý checklist すべてのチェックリストを管理する',
        'module_description' => 'Divide your task in multiple checklists, Mark it complete to track progress of your task.',
        'complete' => 'Hoàn thành 完了',
        'incomplete' => 'Chưa hoàn thành 未完成',
        'props' => [
            'title' => 'Tiêu đề タイトル',
            'description' => 'Mô tả 説明',
            'due' => 'Thời hạn 締切',
            'due_date' => 'Hạn hoàn thành 締切日',
            'due_time' => 'Giờ hoàn thành 締切時間',
            'completed_at' => 'Hoàn thành lúc 完了時点',
            'status' => 'Trạng thái スターテス',
        ],
    ],
    'member' => [
        'member' => 'Thành viên メンバー',
        'module_title' => 'Hiển thị tất cả thành viên すべてのメンバーをリストする',
        'module_description' => 'Giao việc cho người thực hiện. このタスクにアクセスしてアクションを実行できるメンバーをタスクに割り当てます。',
        'could_not_perform_if_owner_selected' => 'Không thể thực hiện được vì thành viên đã được thêm từ trước. タスク所有者がすでにメンバーであるため、この操作を実行できませんでした。',
        'could_not_perform_self_action' => 'Could not perform action on yourself.',
        'props' => [
            'manage_member' => 'Quản lý thành viên メンバーの管理',
            'manage_media' => 'Quản lý file đa phương tiện メディアの管理',
            'manage_checklist' => 'Quản lý checklist チェックリストの管理',
            'manage_task_list' => 'Quản lý danh sách công việc タスクリストの管理',
            'manage_completion' => 'Thay đổi trạng thái công việc タスクを完了/未完了としてマークする',
        ],
    ],
    'media' => [
        'media' => 'Media',
    ],
    'repeat' => [
        'repeat' => 'Repeat',
        'doesnt_repeat' => 'This task is not set to repeat itself.',
        'repeat_on_date' => 'This task will repeat itself on :attribute.',
        'repeat_over' => 'This task will no longer repeat itself.',
        'repeated_task' => 'Repeated Task',
        'frequencies' => [
            'day_wise' => 'Everyday',
            'date_wise' => 'Date wise',
            'day_wise_count' => 'After Every X Day',
            'weekly' => 'Weekly',
            'fortnightly' => 'Fortnightly',
            'monthly' => 'Monthly',
            'bi_monthly' => 'Bi Monthly',
            'quarterly' => 'Quarterly',
            'half_yearly' => 'Half Yearly',
            'yearly' => 'Yearly',
        ],
        'props' => [
            'should_repeat' => 'Enable Repeatation',
            'frequency' => 'Frequency',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'next_repeat_date' => 'Next Repeat Date',
            'days' => 'Days',
            'dates' => 'Dates',
            'day' => 'Day',
        ],
    ],
    'list' => [
        'list' => 'Danh sách công việc タスクリスト',
        'module_title' => 'Quản lý danh sách công việc',
        'module_description' => 'Phân loại công việc để dễ quản lý hơn. タスクをさまざまなリストに分類して、簡単に見つけられるようにします。',
        'uncategorized' => 'Không phân loại 未分類',
        'info_empty' => 'Danh sách trống, hãy kéo/thả công việc vào đây. このリストは空です。 ここにいくつかのタスクをドラッグ アンド ドロップします。',
        'could_not_perform_if_not_permitted' => 'Không thể thực hiện thao tác vì ngoài quyền hạn. 許可されていないため移動できませんでした。',
        'could_not_perform_if_empty_list' => 'Không thể di chuyển công việc chưa được phân loại. 未分類リストに移動できませんでした。',
        'props' => [
            'name' => 'Name',
            'description' => 'Mô tả 説明',
        ],
    ],
    'category' => [
        'category' => 'Danh mục công việc タスクのカテゴリ',
        'module_title' => 'Quản lý danh mục すべてのタスク カテゴリを管理する',
        'module_description' => 'Phân loại công việc theo danh mục riêng. タスクをさまざまなカテゴリに分類します。 元開発、デザイン、アカウントなど ',
        'props' => [
            'name' => 'Name',
            'description' => 'Mô tả 説明',
        ],
    ],
    'priority' => [
        'priority' => 'Độ ưu tiên タスクの優先度',
        'module_title' => 'Quản lý độ ưu tiên すべてのタスクの優先度管理',
        'module_description' => 'Chia công việc theo độ ưu tiên để dễ sắp xếp thực hiện. タスクの優先順位を作成して管理します。 例: 低、中、高、または重大。',
        'props' => [
            'name' => 'Name',
            'description' => 'Mô tả 説明',
        ],
    ],
    'config' => [
        'config' => 'Cài đặt 設定',
        'props' => [
            'view' => 'Hiển thị công việc タスク表示',
            'is_accessible_to_top_level' => 'Quyền hạn xem トップレベルの従業員がアクセスできるタスク',
            'is_manageable_by_top_level' => 'Quyền hạn sửa トップレベルの従業員がタスクを編集可能',
            'number_prefix' => 'Phần bắt đầu mã CV タスクのプレフィックス',
            'number_suffix' => 'Phần sau mã CV タスクのサフィックス',
            'number_digit' => 'Số lượng số trong mã CV タスク番号の桁',
        ],
        'views' => [
            'card' => 'Dạng thẻ カード',
            'list' => 'Danh sách リスト',
            'board' => 'Dạng bảng ボード',
        ],
    ],
];
