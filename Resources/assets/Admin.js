// App
import umbrellaApp from "umbrella_core/Core";

import "metismenu";
import "simplebar"

// components
import Sidebar from "umbrella_admin/components/Sidebar";
import Layout from "umbrella_admin/components/Layout";
import Notification from "umbrella_admin/components/Notification";

umbrellaApp.use('[data-mount=Sidebar]', Sidebar);
umbrellaApp.use('[data-mount=Layout]', Layout);
umbrellaApp.use('[data-mount=Notification]', Notification);

export default umbrellaApp;
