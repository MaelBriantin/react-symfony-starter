import useUserStore from "@/stores/useUserStore";
import GuestLayout from "./GuestLayout";
import AuthenticatedLayout from "./AuthenticatedLayout";

const AppLayout = () => {
  const { user } = useUserStore();

  return (
    <>
      {
        user === null ? (
          <GuestLayout />
        ) : (
          <AuthenticatedLayout />
        )
      }
    </>
  );
}

export default AppLayout;