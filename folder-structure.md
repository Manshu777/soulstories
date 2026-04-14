app/
├── Actions/
│   ├── Content/
│   │   ├── CreateContentAction.php
│   │   ├── UpdateContentAction.php
│   │   └── PublishContentAction.php
│   ├── Promotion/
│   │   └── ApprovePromotionAction.php
│   └── Earn/
│       └── VerifySubmissionAction.php
│
├── DTOs/
│   ├── ContentData.php
│   └── PaymentData.php
│
├── Helpers/
│   └── MoneyHelper.php
│
├── Http/
│   ├── Controllers/
│   │   ├── Api/
│   │   │   ├── AuthController.php
│   │   │   ├── ContentController.php
│   │   │   ├── SeriesController.php
│   │   │   ├── PromotionController.php
│   │   │   ├── EarnController.php
│   │   │   ├── ContestController.php
│   │   │   └── ServiceController.php
│   │   └── Web/
│   │       ├── HomeController.php
│   │       ├── ProfileController.php
│   │       └── DashboardController.php
│   │
│   ├── Middleware/
│   │   ├── IsAdmin.php
│   │   └── IsActiveUser.php
│   │
│   ├── Requests/
│   │   ├── Auth/
│   │   │   ├── LoginRequest.php
│   │   │   └── RegisterRequest.php
│   │   ├── Content/
│   │   │   ├── StoreContentRequest.php
│   │   │   └── UpdateContentRequest.php
│   │   ├── Promotion/
│   │   ├── Earn/
│   │   └── Service/
│   │
│   └── Resources/
│       ├── UserResource.php
│       ├── ContentResource.php
│       ├── PromotionResource.php
│       └── ContestResource.php
│
├── Jobs/
│   ├── ProcessPaymentJob.php
│   ├── SendEmailJob.php
│   └── NotifyFollowersJob.php
│
├── Listeners/
│   └── SendPromotionApprovedNotification.php
│
├── Mail/
│
├── Models/
│   ├── User.php
│   ├── Content.php
│   ├── Series.php
│   ├── Chapter.php
│   ├── Vote.php
│   ├── Comment.php
│   ├── View.php
│   ├── Review.php
│   ├── Blog.php
│   ├── Promotion.php
│   ├── PromotionPackage.php
│   ├── PromotionPayment.php
│   ├── EarnTask.php
│   ├── EarnOrder.php
│   ├── EarnSubmission.php
│   ├── EarnPayment.php
│   ├── Contest.php
│   ├── ContestEntry.php
│   ├── Service.php
│   ├── ServiceOrder.php
│   └── ServicePayment.php
│
├── Notifications/
│   ├── NewFollowerNotification.php
│   ├── ContentPublishedNotification.php
│   ├── PromotionApprovedNotification.php
│   └── ContestResultNotification.php
│
├── Policies/
│   ├── ContentPolicy.php
│   ├── PromotionPolicy.php
│   ├── ContestPolicy.php
│   └── ServicePolicy.php
│
├── Services/
│   ├── ContentService.php
│   ├── PaymentService.php
│   ├── PromotionService.php
│   ├── EarnService.php
│   └── ContestService.php
│
├── Traits/
│   ├── HasSlug.php
│   └── HasMedia.php
│
└── Providers/
    └── AppServiceProvider.php


routes/
├── web.php
├── api.php
├── auth.php
└── admin.php


database/
├── migrations/
├── factories/
└── seeders/
    ├── RoleSeeder.php
    ├── UserSeeder.php
    └── ContentSeeder.php


resources/
├── views/
│   ├── layouts/
│   ├── auth/
│   ├── content/
│   ├── profile/
│   └── admin/
├── js/
└── css/
