package com.capeitassist.fixtrackpro

import android.content.Context
import android.content.Intent
import android.os.Bundle
import android.util.Log
import android.widget.Button
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import com.android.volley.Request
import com.android.volley.toolbox.StringRequest
import com.android.volley.toolbox.Volley
import com.google.android.material.textfield.TextInputEditText
import org.json.JSONObject

class LoginActivity : AppCompatActivity() {

    private lateinit var emailEditText: TextInputEditText
    private lateinit var passwordEditText: TextInputEditText
    private lateinit var loginButton: Button
    private lateinit var registerButton: Button

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_login)

        // Bind exact IDs from your activity_login.xml
        emailEditText = findViewById(R.id.emailEditText)
        passwordEditText = findViewById(R.id.passwordEditText)
        loginButton = findViewById(R.id.loginButton)
        registerButton = findViewById(R.id.registerButton)

        loginButton.setOnClickListener {
            performLogin()
        }

        registerButton.setOnClickListener {
            val intent = Intent(this@LoginActivity, RegisterActivity::class.java)
            startActivity(intent)

        }
    }

    private fun performLogin() {
        val email = emailEditText.text.toString().trim()
        val password = passwordEditText.text.toString().trim()

        if (email.isEmpty() || password.isEmpty()) {
            Toast.makeText(this, "Please fill in all fields", Toast.LENGTH_SHORT).show()
            return
        }

        loginButton.isEnabled = false
        loginButton.text = "Logging in..."

        val url = "${NetworkConfig.BASE_URL}login.php"
        val queue = Volley.newRequestQueue(this)

        val stringRequest = object : StringRequest(
            Request.Method.POST, url,
            { response ->
                loginButton.isEnabled = true
                loginButton.text = "Log In"

                try {
                    val jsonResponse = JSONObject(response)
                    val status = jsonResponse.optString("status")

                    if (status == "success") {
                        val userObj = jsonResponse.getJSONObject("user")
                        val userId = userObj.getInt("id")
                        val userName = userObj.getString("name")
                        val userEmail = userObj.getString("email")
                        val userRole = userObj.getString("role").trim()

                        Log.d("FixTrackLogin", "Logged in Role: '$userRole'")

                        // Store session in SharedPreferences
                        val sharedPref = getSharedPreferences("FixTrackPrefs", Context.MODE_PRIVATE)
                        with(sharedPref.edit()) {
                            putInt("user_id", userId)
                            putString("user_name", userName)
                            putString("user_email", userEmail)
                            putString("user_role", userRole)
                            apply()
                        }

                        Toast.makeText(this, "Welcome back, $userName!", Toast.LENGTH_SHORT).show()

                        // Route to AdminPanelActivity or GuestFeedActivity based on role
                        if (userRole.equals("Admin", ignoreCase = true)) {
                            val intent = Intent(this@LoginActivity, AdminPanelActivity::class.java)
                            startActivity(intent)
                            finish()
                        } else {
                            val intent = Intent(this@LoginActivity, GuestFeedActivity::class.java)
                            startActivity(intent)
                            finish()
                        }

                    } else {
                        val message = jsonResponse.optString("message", "Login failed")
                        Toast.makeText(this@LoginActivity, message, Toast.LENGTH_LONG).show()
                    }

                } catch (e: Exception) {
                    e.printStackTrace()
                    Toast.makeText(this@LoginActivity, "JSON parsing error: ${e.message}", Toast.LENGTH_SHORT).show()
                }
            },
            { error ->
                loginButton.isEnabled = true
                loginButton.text = "Log In"
                Toast.makeText(this@LoginActivity, "Network Error: ${error.message}", Toast.LENGTH_SHORT).show()
            }
        ) {
            override fun getParams(): MutableMap<String, String> {
                val params = HashMap<String, String>()
                params["email"] = email
                params["password"] = password
                return params
            }
        }

        queue.add(stringRequest)
    }
}