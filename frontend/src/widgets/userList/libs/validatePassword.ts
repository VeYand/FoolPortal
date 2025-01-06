const validatePassword = (value: string, required: boolean): Promise<void> => {
	if (!required && !value) {
		return Promise.resolve()
	}
	if (!value) {
		return Promise.reject(new Error('Пожалуйста, введите пароль!'))
	}
	if (value.length < 8) {
		return Promise.reject(new Error('Пароль должен содержать не менее 8 символов.'))
	}
	if (value.length > 64) {
		return Promise.reject(new Error('Пароль должен быть не более 64 символов.'))
	}
	if (!/[A-Z]/.test(value)) {
		return Promise.reject(new Error('Пароль должен содержать хотя бы одну заглавную букву.'))
	}
	if (!/[a-z]/.test(value)) {
		return Promise.reject(new Error('Пароль должен содержать хотя бы одну строчную букву.'))
	}
	if (!/[0-9]/.test(value)) {
		return Promise.reject(new Error('Пароль должен содержать хотя бы одну цифру.'))
	}
	return Promise.resolve()
}

export {
	validatePassword,
}