import {Middleware} from '@reduxjs/toolkit'
import {userEntitySlice} from 'entities/user'
import {LoginRoute} from 'shared/routes'

const authorizationMiddleware: Middleware = _ => next => action => {
	if (isUnauthorizedError(action)) {
		handleUnauthorizedAccess()
	}

	return next(action)
}

const isUnauthorizedError = (action: any): boolean => (
	action.meta?.requestStatus === 'rejected'
	&& action.error?.message
	&& getStatusFromErrorMessage(action.error.message) === '401'
)

const handleUnauthorizedAccess = () => {
	userEntitySlice.actions.setInitialized(false)

	if (window.location.pathname !== LoginRoute.path) {
		window.location.replace(window.location.origin + LoginRoute.path)
	}
}

const getStatusFromErrorMessage = (message: string) => (message.split(' ').pop() as string)

export {
	authorizationMiddleware,
}