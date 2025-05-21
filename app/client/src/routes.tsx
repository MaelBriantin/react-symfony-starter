import About from "@/pages/About";
import Login from "@/pages/Login";
import AppLayout from "./layouts/AppLayout";
import SignUp from "./pages/SignUp";

const routes = [
  {
    element: <AppLayout />,
    children: [
      { path: "/", element: <About /> },
      {
        path: "/login",
        element: <Login />,
      },
      {
        path: "/signup",
        element: <SignUp />,
      },
    ],
  },
  
];

export default routes;