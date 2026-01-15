import React, { useCallback, useContext, useEffect, useState } from "react";
import { useNavigate, Link } from "react-router-dom";
import logo from "../../assests/Kofsoft.jpg";
import "../Auth/Auth.css";
import { loader, loadtime } from "../../utils/loader";
import { useTranslation } from "react-i18next";
import { FaEye, FaEyeSlash } from "react-icons/fa";
import { axiosPrivate } from "../../api/axios";
import axios from "axios";
import { AppContext } from "../../Context/AppContext";
import { getCsrfCookie } from "../../utils/api";

const API_URL = "http://localhost:8000";

axios.defaults.baseURL = API_URL;
axios.defaults.withCredentials = true;

const Login = () => {
  const [rememberMe, setRememberMe] = useState(false);
  const { setRestaurant, setUser, setWaiters, setCustomer } =
    useContext(AppContext);
  const { t } = useTranslation();
  const navigate = useNavigate();
  const [load, setLoad] = useState(false);
  const [showPassword, setShowPassword] = useState(false);
  const [formData, setFormData] = useState({
    email: "",
    password: "",
  });
  const [error, setError] = useState({});
  const from = location.state?.from?.pathname || "/";

  const clearErrors = () => setError({});
  const togglePasswordVisibility = () => {
    setShowPassword((prev) => !prev);
  };

  const checkAuthAndFetchData = useCallback(async () => {
    try {
      const userResponse = await axios.get("/api/user");
      const userData = userResponse.data;
      console.log(userData);
    } catch (error) {
      console.log("Not authenticated");
    }
  }, []);

  useEffect(() => {
    checkAuthAndFetchData();
  }, [checkAuthAndFetchData]);

  // const handleLogin = async (e) => {
  //   e.preventDefault();
  //   setLoad(true);
  //   await getCsrfCookie();

  //   try {
  //     if (!formData.email || !formData.password) {
  //       loadtime(200, setLoad);
  //       if (!formData.email && !formData.password) {
  //         setError({ empty: t("Please fill all form") });
  //       } else if (!formData.email) {
  //         setError({ emailEmpty: t("Email is required") });
  //       } else {
  //         setError({ passwordEmpty: t("Password is required") });
  //       }
  //       setFormData((prev) => ({ ...prev, password: "" }));
  //       return;
  //     }

  //     await axios.get("http://localhost:8000/sanctum/csrf-cookie", {
  //       withCredentials: true,
  //     });
  //     const response = await axiosPrivate.post(
  //       "/api/login",
  //       JSON.stringify(formData),
  //       {
  //         headers: { "Content-Type": "application/json" },
  //         withCredentials: true,
  //       }
  //     );

  //     if (response.data.errors) {
  //       loadtime(200, setLoad);
  //       setError(response.data.errors);
  //       return;
  //     }
  //     localStorage.setItem("token", response.data.token);
  //     localStorage.setItem("loggedIn", "true");
  //     localStorage.setItem(
  //       "_rPColor",
  //       JSON.stringify(response.data.user.restaurant.primaryColor)
  //     );
  //     localStorage.setItem(
  //       "_rSColor",
  //       JSON.stringify(response.data.user.restaurant.secondaryColor)
  //     );
  //     setUser(response.data.user);
  //     setRestaurant(response.data.user.restaurant);
  //     setWaiters(response.data.user.restaurant.waiters);
  //     setCustomer(response.data.user.restaurant.customers);
  //     loadtime(700, setLoad);
  //     navigate(from, { replace: true });
  //     window.location.reload();
  //   } catch (error) {
  //     loadtime(1200, setLoad);
  //     if (error.status === 401) {
  //       setError({ empty: t(error.response.data.message) });
  //       setError({ email: t("Unknown error") });
  //       // setFormData((prev) => ({ ...prev, password: "" }));
  //       return;
  //     }
  //     if (error?.response?.data?.errors) {
  //       setError(error.response.data.errors);
  //     } else if (error?.response) {
  //       setError({ server: error.response });
  //     } else {
  //       setError({ server: "Unknown Credentials" });
  //     }
  //     navigate("/login");
  //   }
  // };

  const handleLogin = async (e) => {
    e.preventDefault();
    await getCsrfCookie();
    try {
      await axios.post("/api/login", JSON.stringify(formData), {
        headers: { "Content-Type": "application/json" },
      });
      await checkAuthAndFetchData();
    } catch (error) {
      const msg =
        error.response?.data?.message || "Login failed. Check credentials.";
      console.error(msg);
    }
  };
  const hasError =
    error.server ||
    error.passwordEmpty ||
    error.emailEmpty ||
    error.crendentias ||
    error.password ||
    error.empty ||
    error.email;

  return (
    <div className="auth-container" style={{ flexDirection: "column" }}>
      {hasError && (
        <div
          style={{
            margin: "10px 0",
            padding: "10px",
            backgroundColor: "lightpink",
          }}
        >
          <p style={{ color: "white", textAlign: "center" }}>
            {error.server ? t("Internal Server Error") : ""}
            {error.passwordEmpty ? t(error.passwordEmpty) : ""}
            {error.emailEmpty ? t(error.emailEmpty) : ""}
            {error.empty ? t(error.empty) : ""}
            {error.password || error.email ? t("Incorrect Credentials") : ""}
          </p>
        </div>
      )}
      <div>
        <div id="logo">
          <img src={logo} alt="k" />
          <Link to="/">Kofsoft</Link>
        </div>
        <div>
          <h1 className="text-3xl">{t("Welcome Back")}!</h1>
        </div>

        <form onSubmit={handleLogin}>
          <div>
            <div>
              <div>
                <label htmlFor="email">{t("Email")}:</label>
              </div>
              <input
                onFocus={clearErrors}
                id="email"
                type="email"
                name="email"
                autoComplete="username"
                value={formData.email}
                onChange={(e) =>
                  setFormData({ ...formData, email: e.target.value })
                }
                placeholder={t("Enter your email")}
                className={` ${error.emailEmpty || error.empty ? "error" : ""}`}
              />
            </div>

            <div
              style={{ position: "relative", width: "100%" }}
              className="password-container"
            >
              <div>
                <label htmlFor="password">{t("Password")}:</label>
              </div>
              <input
                id="password"
                type={showPassword ? "text" : "password"}
                onFocus={clearErrors}
                name="password"
                autoComplete="current-password"
                value={formData.password}
                onChange={(e) =>
                  setFormData({ ...formData, password: e.target.value })
                }
                placeholder={t("Enter your password")}
                className={`${error.passwordEmpty ? "error" : ""}`}
                style={{
                  width: "100%",
                  paddingRight: "40px",
                }}
              />
              <span
                onClick={togglePasswordVisibility}
                style={{
                  position: "absolute",
                  right: "15px",
                  top: "70%",
                  transform: "translateY(-50%)",
                  cursor: "pointer",
                  color: "#888",
                  fontSize: "1.2rem",
                }}
              >
                {showPassword ? <FaEyeSlash /> : <FaEye />}
              </span>
            </div>

            <div className="remember-me">
              <input
                type="checkbox"
                id="remember-me"
                className="checkbox"
                checked={rememberMe}
                onChange={(e) => setRememberMe(e.target.checked)}
              />
              <span>
                <p>{t("Remember Me")}</p>
              </span>
            </div>

            <div>
              <button type="submit">
                {t("login")}
                {loader(load)}
              </button>
            </div>
            <div id="forget-password">
              <span>{t("Forgot your password")}?</span>
              <Link to="/forget-password" id="links">
                {t("Reset it here")}
              </Link>
            </div>

            <div id="forget-password">
              <Link to="/becomeMerchant" id="links">
                <span>{t("Don't Have Account Yet")}?</span>
              </Link>
            </div>
          </div>
        </form>
      </div>
    </div>
  );
};

export default Login;
