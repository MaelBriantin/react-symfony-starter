import useUserStore from "@/stores/useUserStore";
import GuestLayout from "./GuestLayout";

const AppLayout = () => {
  const { user } = useUserStore();

  return (
    <div className="flex flex-col h-screen overflow-hidden w-full"> 
      {
        user === null ? (
          <GuestLayout />
        ) : (
          <GuestLayout />
        )
      }
    </div>
  );
}

export default AppLayout;