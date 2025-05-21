import About from "@/pages/About";
import Login from "@/pages/Login";
import AppLayout from "./layouts/AppLayout";

const routes = [
  {
    element: <AppLayout />,
    children: [
      { path: "/", element: <About /> },
    ],
  },
  {
    path: "/login",
    element: <Login />, // Page login sans layout
  },
];

export default routes;