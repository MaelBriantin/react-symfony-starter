import { useState } from "react";
import useFetch from "../../hooks/useFetch";
import { useNavigate } from "react-router";
import useUserStore from "@/stores/useUserStore";
import { User } from "@/types/user";
import { Input, Button } from "@/components/globals";
import { ButtonType, InputType } from "@/components/globals/types";

interface LoginFormProps {
  username: string;
  password: string;
}

const LoginForm = () => {
  const { loading, error, fetchData } = useFetch<User, LoginFormProps>();
  const { setUser } = useUserStore();
  const redirect = useNavigate();
  const [loginInfo, setLoginInfo] = useState<LoginFormProps>({
    username: "test@example.com",
    password: "Password123!",
  });

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    const user = await fetchData({
      url: "/auth/login",
      method: "POST",
      body: loginInfo
    });
    if (!error) {
      setUser(user);
      redirect("/");
    }
  }

  return (
    <div className="dark:bg-neutral-900 dark:text-white bg-white text-black min-h-screen flex items-center justify-center font-geist-mono flex-col gap-2 w-full p-6">
      <h1>Login Form</h1>
      <form className="flex flex-col gap-4 w-full max-w-sm" onSubmit={handleSubmit}>
        <Input
          label="Email:"
          type={InputType.EMAIL}
          name="username"
          required
          value={loginInfo.username}
          onChange={e => setLoginInfo({
            ...loginInfo,
            username: e.target.value
          })}
        />
        <Input
          label="Password:"
          type={InputType.PASSWORD}
          name="password"
          required
          value={loginInfo.password}
          onChange={e => setLoginInfo({
            ...loginInfo,
            password: e.target.value
          })}
        />
        {error && <div className="text-red-500">{error.message}</div>}
        <Button
          loading={loading}
          loadingText="Logging in..."
          type={ButtonType.SUBMIT}
          >
          Login
        </Button>
      </form>
    </div>
  );
}

export default LoginForm;