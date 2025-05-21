import Button, { ButtonType } from "@/components/Button";
import Input, { InputType } from "@/components/Input";
import { useState } from "react";
import useFetch from "@/hooks/useFetch";
import { useNavigate } from "react-router";
import { User } from "@/types/user";

interface SignUpFormProps {
  password: string;
  email: string;
}

interface LoginFormProps {
  username: string;
  password: string;
}

const SignUpForm = () => {
  const { loading: loadingSignup, error: errorSignUp, fetchData: fetchSignUp } = useFetch<User, SignUpFormProps>();
  const { loading: loadingLogin, fetchData: fetchLogin } = useFetch<User, LoginFormProps>();
  const [signUpInfo, setSignUpInfo] = useState<SignUpFormProps>({
    email: "",
    password: "",
  });
  const navigate = useNavigate();

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    await fetchSignUp({
      url: "/auth/register",
      method: "POST",
      body: signUpInfo
    });
    if (!errorSignUp) {
      await fetchLogin({
        url: "/auth/login",
        method: "POST",
        body: {
          username: signUpInfo.email,
          password: signUpInfo.password
        }
      });
      if (!errorSignUp) {
        navigate("/");
      }
    }
  }

  return (
    <div className="dark:bg-neutral-900 dark:text-white bg-white text-black min-h-screen flex items-center justify-center font-geist-mono flex-col gap-2 w-full p-6">
      <h1>SignUp Form</h1>
      <form className="flex flex-col gap-4 w-full max-w-sm" onSubmit={handleSubmit}>
        <Input
          label="Email:"
          type={InputType.EMAIL}
          name="username"
          required
          value={signUpInfo.email}
          onChange={e => setSignUpInfo({
            ...signUpInfo,
            email: e.target.value
          })}
        />
        <Input
          label="Password:"
          type={InputType.PASSWORD}
          name="password"
          required
          value={signUpInfo.password}
          onChange={e => setSignUpInfo({
            ...signUpInfo,
            password: e.target.value
          })}
        />
        {errorSignUp && <div className="text-red-500">{errorSignUp.message}</div>}
        <Button
          loading={loadingLogin || loadingSignup}
          loadingText="Loading..."
          type={ButtonType.SUBMIT}
          >
          SignUp
        </Button>
      </form>
    </div>
  );
}

export default SignUpForm;