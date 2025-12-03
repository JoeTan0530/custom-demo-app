<!DOCTYPE html>
<html>
	<?php include "./assets/config/config.php"?>
	<?php include "head.php"?>
	<style>
		html {
			height: 100%;
		}

		body {
			display: block;
			height: 100%;
			background-color: #FFFFFF;
		}

		a {
			color: #8ff;
		}

		button {
			color: rgba(127,255,255,0.75);
			background: transparent;
			border: unset;
			padding: 5px 10px;
			cursor: pointer;
		}

		button:hover {
			background-color: rgba(0,255,255,0.5);
		}

		button:active {
			color: #000000;
			background-color: rgba(0,255,255,0.75);
		}

		.login-component-container {
			display: flex;
			justify-content: center;
			align-items: center;
			flex-direction: column;
			padding: 5rem 3rem;
			border: 1px solid #E3E3E3;
		}

		h1 {
			font-size: 30px;
			font-weight: 500;
			color: #000;
		}

		h2 {
			font-size: 15px;
			font-weight: 300;
			color: #000;
		}

		button.custom-btn {
			border-radius: 15px;
			padding: 0.3rem 1rem 0.3rem 0.5rem;
			background-color: #5292F2;
			color: #FFFFFF;
			font-size: 17px;
			font-weight: 300;
			display: flex;
			justify-content: center;
			align-items: center;
		}

		.btn-logo {
			width: 35px;
			height: auto;
		}
	</style>
	<body>
		<div class="d-flex justify-content-center align-items-center w-100 h-100">
			<div class="login-component-container">
				<h1>
					Welcome Back
				</h1>
				<h2 class="mb-4">
					Lorem ipsum dolor sit amet, consectetur adipiscing elit.
				</h2>
				<button type="button" class="custom-btn" onclick="triggerGoogleLogin();">
					<img src="./assets/images/google-logo-transparent.webp" class="btn-logo"/>Sign in with Google
				</button>
			</div>
		</div>

		<!-- Google API Script -->
    	<script src="https://accounts.google.com/gsi/client" async defer></script>

		<script>
			// Replace with your actual Google Client ID
	        const GOOGLE_CLIENT_ID = "<?php echo $config['googleLoginID'];?>";
	        // const GOOGLE_CLIENT_ID = "81189356182-5r6t3524elf27s1ajpuqtq0vp52i0dgn.apps.googleusercontent.com";
	        let tokenClient;
	        let userData = null;

	        // Initialize Google Auth
	        function initializeGoogleAuth() {
	            tokenClient = google.accounts.oauth2.initTokenClient({
	                client_id: GOOGLE_CLIENT_ID,
	                scope: 'https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email',
	                callback: (tokenResponse) => {
	                    if (tokenResponse && tokenResponse.access_token) {
	                        getUserInfo(tokenResponse.access_token);
	                    }
	                },
	            });
	        }

	        // Handle login button click
	        function triggerGoogleLogin() {
	            tokenClient.requestAccessToken();
	        }

	        // Get user info from Google API
	        async function getUserInfo(accessToken) {
	            try {
	                const response = await fetch('https://www.googleapis.com/oauth2/v3/userinfo', {
	                    headers: {
	                        'Authorization': `Bearer ${accessToken}`
	                    }
	                });
	                
	                if (!response.ok) {
	                    throw new Error('Failed to fetch user info');
	                }


	                
	                userData = await response.json();
	                storeUserInfo(userData, accessToken);
	                
	            } catch (error) {
	                console.error('Error fetching user info:', error);
	                alert('Failed to get user information. Please try again.');
	            }
	        }

	        // Store user information
	        async function storeUserInfo(user, token) {;
	        	const formData = new FormData();
	            formData.append('user_data', JSON.stringify(user));
	            formData.append('token_data', token);
	            
	            const response = await fetch('saveUserInfo.php', {
	                method: 'POST',
	                body: formData
	            });
	            
	            if (response.ok) {
	                window.location.href = "demo-table.php";
	            }
	        }

	        // Check if user is already logged in on page load
	        function checkExistingLogin() {
	            const savedUser = localStorage.getItem('googleUser');
	            if (savedUser) {
	                userData = JSON.parse(savedUser);
	                storeUserInfo(userData);
	            }
	        }

	        // Initialize when Google API loads
	        window.onload = function() {
	            // Google API loads the gsi client, then we initialize
	            setTimeout(() => {
	                initializeGoogleAuth();
	                // checkExistingLogin();
	            }, 100);
	        };
		</script>
	</body>
</html>