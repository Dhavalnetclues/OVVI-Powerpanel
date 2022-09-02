<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(RoleUserTableSeeder::class);
        $this->call(ModuleTableSeeder::class);
        $this->call(ImageTableSeeder::class);
        $this->call(CmsPageTableSeeder::class);
        $this->call(MenuTypeSeeder::class);
        $this->call(AliasTableSeeder::class);
        $this->call(EmailTypeTableSeeder::class);
        if (file_exists(database_path('seeds') . '\ShiledGeneralSettingsTableSeeder.php') != null) {
            $this->call(ShiledGeneralSettingsTableSeeder::class);
        } else {
            $this->call(GeneralSettingsTableSeeder::class);
        }
        $this->call(CurrencyTableSeeder::class);
        $this->call(PermissionTableSeeder::class);
        $this->call(PermissionRoleTableSeeder::class);
        $this->call(ZoneTableSeeder::class);
        $this->call(SiteMonitorTableSeeder::class);
        $this->call(ModuleGroupTableSeeder::class);
        $this->call(VisualComposerTableSeeder::class);

        $this->call(MenuTableSeeder::class);
        $this->call(CmsPageTableSeeder::class);
        $this->call(StaticBlocksTableSeeder::class);
        $this->call(ContactInfoTableSeeder::class);
        $this->call(RoleManagerTableSeeder::class);
        $this->call(BlockedIPTableSeeder::class);
        $this->call(MessagingSystemTableSeeder::class);
        $this->call(PageTemplatesTableSeeder::class);
        $this->call(TicketListTableSeeder::class);
        $this->call(FeedbackLeadTableSeeder::class);
        $this->call(SearchStaticticsReportTableSeeder::class);
        $this->call(HitsReportTableSeeder::class);
        $this->call(DocumentReportTableSeeder::class);
        $this->call(FormBuilderTableSeeder::class);
        $this->call(FormBuilderLeadTableSeeder::class);
        $this->call(LiveUserTableSeeder::class);
        $this->call(WorkflowTableSeeder::class);
        $this->call(BannerTableSeeder::class);
        $this->call(BlogsTableSeeder::class);
        $this->call(BlogCategoryTableSeeder::class);
        $this->call(NewsTableSeeder::class);
        $this->call(NewsCategoryTableSeeder::class);
        $this->call(EventsTableSeeder::class);
        $this->call(EventCategoryTableSeeder::class);
        $this->call(PhotoAlbumTableSeeder::class);
        $this->call(PhotoGalleryTableSeeder::class);
        $this->call(ContactUsLeadTableSeeder::class);
        $this->call(ServicesTableSeeder::class);
        $this->call(TeamTableSeeder::class);
        $this->call(NewsletterLeadTableSeeder::class);
        $this->call(ServicesCategoryTableSeeder::class);
        $this->call(AlertsTableSeeder::class);
        $this->call(OrganizationsTableSeeder::class);
        $this->call(DepartmentTableSeeder::class);
        $this->call(QuickLinksTableSeeder::class);
        $this->call(LinksCategoryTableSeeder::class);
        $this->call(LinksTableSeeder::class);
        $this->call(FaqCategoryTableSeeder::class);
        $this->call(FaqTableSeeder::class);
        $this->call(PublicationsCategoryTableSeeder::class);
        $this->call(PublicationsTableSeeder::class);
        $this->call(CareersTableSeeder::class);
        $this->call(VideoGalleryTableSeeder::class);
#=
        #==
    }
}
