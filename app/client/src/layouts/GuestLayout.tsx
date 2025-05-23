import NavBar from "@/components/globals/NavBar";
import { Outlet } from "react-router";

const GuestLayout = () => {
  return (
    <div>
      <div className="dark:bg-neutral-900 dark:text-white bg-white text-black min-h-screen flex items-center justify-center font-geist-mono flex-col gap-2 w-full p-6">
        <NavBar />
        <Outlet />
      </div>
    </div>
  );
}
export default GuestLayout;