import {configureStore} from '@reduxjs/toolkit'
import {userEntitySlice} from 'entities/user'
import {apiSlice} from './api'
import {authorizationMiddleware} from './middleware'

const store = configureStore({
	reducer: {
		userEntity: userEntitySlice.reducer,
		[apiSlice.reducerPath]: apiSlice.reducer,
	},
	middleware: getDefaultMiddleware =>
		getDefaultMiddleware().concat(authorizationMiddleware),
})

type AppDispatch = typeof store.dispatch
type RootState = ReturnType<typeof store.getState>

export {
	store,
	type AppDispatch,
	type RootState,
}